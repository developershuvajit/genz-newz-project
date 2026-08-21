<?php
class Auth {
    public static function login($user) {
        Session::set('user_id', $user['id']);
        Session::set('user_role', $user['role']);
        Session::set('user_name', $user['name']);
        Session::set('user_email', $user['email']);
        Session::regenerate();
    }
    
    public static function logout() {
        Session::destroy();
    }
    
    public static function isLoggedIn() {
        return Session::has('user_id');
    }
    
    public static function user() {
        if (!self::isLoggedIn()) return null;
        $db = Model::getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([Session::get('user_id')]);
        return $stmt->fetch();
    }
    
    public static function id() {
        return Session::get('user_id');
    }
    
    public static function role() {
        return Session::get('user_role');
    }
    
    public static function isAdmin() {
        return self::isLoggedIn() && self::role() === 'admin';
    }
    
    public static function isReporter() {
        return self::isLoggedIn() && self::role() === 'reporter';
    }
    
    public static function requireAdmin() {
        if (!self::isAdmin()) {
            Helper::redirect('/admin/login');
        }
    }
    
    public static function requireReporter() {
        if (!self::isReporter()) {
            Helper::redirect('/reporter/login');
        }
    }
    
    public static function requireGuest() {
        if (self::isLoggedIn()) {
            if (self::isAdmin()) Helper::redirect('/admin/dashboard');
            if (self::isReporter()) Helper::redirect('/reporter/dashboard');
        }
    }
    
    public static function regenerate() {
        session_regenerate_id(true);
    }
    
    public static function check() {
        return self::isLoggedIn();
    }
}
?>