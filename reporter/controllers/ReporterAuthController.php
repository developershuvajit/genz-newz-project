<?php
/**
 * GenzNewz — Reporter Auth Controller
 */

declare(strict_types=1);

class ReporterAuthController extends Controller {
    public function login(): void {
        if (Auth::isReporter()) {
            $this->redirect('/reporter/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::verify();
            $identity = trim($_POST['identity'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (Auth::attempt($identity, $password, ROLE_REPORTER) || Auth::attempt($identity, $password, ROLE_ADMIN)) {
                Session::setFlash('success', 'স্বাগতম রিপোর্টার প্যানেলে!');
                $this->redirect('/reporter/dashboard');
            } else {
                Session::setFlash('error', 'ভুল রিপোর্টার আইডি / ইমেইল বা পাসওয়ার্ড।');
                $this->redirect('/reporter/login');
            }
        }

        $this->view('reporter/views/login', [
            'pageTitle' => 'সাংবাদিক / রিপোর্টার লগইন — ' . APP_NAME
        ]);
    }

    public function logout(): void {
        Auth::logout();
        Session::setFlash('success', 'রিপোর্টার প্যানেল থেকে সফলভাবে লগআউট হয়েছেন।');
        $this->redirect('/reporter/login');
    }
}
