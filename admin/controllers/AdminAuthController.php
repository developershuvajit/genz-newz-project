<?php
/**
 * GenzNewz — Admin Auth Controller
 */

declare(strict_types=1);

class AdminAuthController extends Controller {
    public function login(): void {
        if (Auth::isAdmin()) {
            $this->redirect('/admin/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::verify();
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (Auth::attempt($email, $password, ROLE_ADMIN)) {
                Session::setFlash('success', 'স্বাগতম অ্যাডমিন প্যানেলে!');
                $this->redirect('/admin/dashboard');
            } else {
                Session::setFlash('error', 'ভুল অ্যাডমিন ইমেইল বা পাসওয়ার্ড।');
                $this->redirect('/admin/login');
            }
        }

        $this->view('admin/views/login', [
            'pageTitle' => 'সুপার অ্যাডমিন লগইন — ' . APP_NAME
        ]);
    }

    public function logout(): void {
        Auth::logout();
        Session::setFlash('success', 'অ্যাডমিন প্যানেল থেকে সফলভাবে লগআউট হয়েছেন।');
        $this->redirect('/admin/login');
    }

    public function profile(): void {
        Auth::requireAdmin();
        $user = Auth::user();

        $this->view('admin/views/profile/index', [
            'pageTitle' => 'অ্যাডমিন প্রোফাইল সেটিংস',
            'user' => $user
        ]);
    }

    public function updateProfile(): void {
        Auth::requireAdmin();
        CSRF::verify();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $newPassword = $_POST['new_password'] ?? '';

        $updateData = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone
        ];

        if (!empty($newPassword)) {
            $updateData['password'] = password_hash($newPassword, PASSWORD_BCRYPT);
        }

        User::update(Auth::id(), $updateData);
        Auth::logActivity('ADMIN_PROFILE_UPDATED', 'Admin profile updated.');
        Session::setFlash('success', 'প্রোফাইল সফলভাবে আপডেট করা হয়েছে।');
        $this->redirect('/admin/profile');
    }
}
