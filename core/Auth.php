<?php
/**
 * GenzNewz — Authentication & Role-Based Authorization
 */

declare(strict_types=1);

class Auth {
    private static ?array $cachedUser = null;

    public static function attempt(string $identity, string $password, ?string $role = null): bool {
        $db = Database::getConnection();

        // Check user by email OR phone OR reporter_id
        $sql = "SELECT u.*, rp.reporter_id, rp.designation, rp.assigned_area, rp.id_card_status 
                FROM users u 
                LEFT JOIN reporter_profiles rp ON u.id = rp.user_id 
                WHERE (u.email = :identity OR u.phone = :identity OR rp.reporter_id = :identity) 
                AND u.status = 'active' LIMIT 1";

        $stmt = $db->prepare($sql);
        $stmt->execute([':identity' => $identity]);
        $user = $stmt->fetch();

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        if ($role !== null && $user['role'] !== $role) {
            return false;
        }

        // Login success
        Session::start();
        Session::set('user_id', $user['id']);
        Session::set('user_role', $user['role']);
        Session::set('user_name', $user['name']);
        Session::set('user_email', $user['email']);

        // Update last login
        $upd = $db->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
        $upd->execute([$user['id']]);

        // Log activity
        self::logActivity('USER_LOGIN', 'User logged in successfully.', (int)$user['id'], $user['name']);

        self::$cachedUser = $user;
        return true;
    }

    public static function login(array $user): void {
        Session::start();
        Session::set('user_id', $user['id']);
        Session::set('user_role', $user['role']);
        Session::set('user_name', $user['name']);
        Session::set('user_email', $user['email']);
        self::$cachedUser = $user;
    }

    public static function logout(): void {
        if (self::check()) {
            self::logActivity('USER_LOGOUT', 'User logged out.', self::id(), self::user()['name'] ?? 'User');
        }
        Session::destroy();
        self::$cachedUser = null;
    }

    public static function check(): bool {
        Session::start();
        return Session::has('user_id');
    }

    public static function id(): ?int {
        Session::start();
        $id = Session::get('user_id');
        return $id ? (int)$id : null;
    }

    public static function role(): ?string {
        Session::start();
        return Session::get('user_role');
    }

    public static function isAdmin(): bool {
        return self::check() && self::role() === ROLE_ADMIN;
    }

    public static function isReporter(): bool {
        return self::check() && (self::role() === ROLE_REPORTER || self::role() === ROLE_ADMIN);
    }

    public static function user(): ?array {
        if (!self::check()) {
            return null;
        }

        if (self::$cachedUser === null) {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT u.*, rp.reporter_id, rp.employee_code, rp.full_name, rp.designation, rp.assigned_area, rp.joining_date, rp.valid_until, rp.id_card_status, rp.blood_group, rp.emergency_contact, rp.address 
                                  FROM users u 
                                  LEFT JOIN reporter_profiles rp ON u.id = rp.user_id 
                                  WHERE u.id = ? LIMIT 1");
            $stmt->execute([self::id()]);
            self::$cachedUser = $stmt->fetch() ?: null;
        }

        return self::$cachedUser;
    }

    public static function reporterProfile(): ?array {
        if (!self::check()) {
            return null;
        }
        $userId = self::id();
        return $userId ? ReporterProfile::findByUserId($userId) : null;
    }

    public static function requireAdmin(): void {
        if (!self::isAdmin()) {
            Session::setFlash('error', 'অ্যাক্সেস অস্বীকৃত: এই পৃষ্ঠাটির জন্য অ্যাডমিন অনুমতি প্রয়োজন।');
            if (!headers_sent()) {
                header('Location: /admin/login');
                exit;
            }
        }
    }

    public static function requireReporter(): void {
        if (!self::isReporter()) {
            Session::setFlash('error', 'অনুগ্রহ করে প্রথমে রিপোর্টার অ্যাকাউন্টে লগইন করুন।');
            if (!headers_sent()) {
                header('Location: /reporter/login');
                exit;
            }
        }
    }

    public static function requireGuest(?string $redirect = '/'): void {
        if (self::check()) {
            if (!headers_sent()) {
                if (self::isAdmin()) {
                    header('Location: /admin/dashboard');
                } else {
                    header('Location: /reporter/dashboard');
                }
                exit;
            }
        }
    }

    public static function logActivity(string $action, string $details = '', ?int $userId = null, ?string $userName = null): void {
        try {
            $db = Database::getConnection();
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            $stmt = $db->prepare("INSERT INTO activity_logs (user_id, user_name, action, details, ip_address, created_at) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
            $stmt->execute([$userId ?: self::id(), $userName ?: (self::user()['name'] ?? 'Guest'), $action, $details, $ip]);
        } catch (Exception $e) {
            // Silently fail if logging fails
        }
    }
}
