<?php
/**
 * GenzNewz — CSRF Protection Handler
 */

class CSRF {
    public static function generateToken(): string {
        Session::start();
        if (!Session::has('csrf_token')) {
            $token = bin2hex(random_bytes(32));
            Session::set('csrf_token', $token);
        }
        return Session::get('csrf_token');
    }

    public static function getToken(): string {
        return self::generateToken();
    }

    public static function validateToken(?string $token): bool {
        Session::start();
        $storedToken = Session::get('csrf_token');
        if (!$storedToken || !$token) {
            return false;
        }
        return hash_equals($storedToken, $token);
    }

    public static function field(): string {
        $token = self::generateToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function verify(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            if (!self::validateToken($token)) {
                http_response_code(403);
                die('403 Forbidden: Invalid or expired CSRF token. Please refresh the page and try again.');
            }
        }
    }
}
