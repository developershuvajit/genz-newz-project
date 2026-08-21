<?php
/**
 * GenzNewz — Reporter Profile Controller
 */

declare(strict_types=1);

class ReporterProfileController extends Controller {
    public function index(): void {
        Auth::requireReporter();

        $userId = Auth::id();
        $user = Auth::user();
        $profile = ReporterProfile::findByUserId($userId);

        $this->view('reporter/views/profile/index', [
            'pageTitle' => 'আমার প্রোফাইল সেটিংস',
            'user' => $user,
            'profile' => $profile
        ]);
    }

    public function update(): void {
        Auth::requireReporter();
        CSRF::verify();

        $userId = Auth::id();
        $user = Auth::user();
        $profile = ReporterProfile::findByUserId($userId);

        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $emergencyContact = trim($_POST['emergency_contact'] ?? '');

        if (!empty($_POST['new_password'])) {
            User::update($userId, [
                'phone' => $phone,
                'password' => password_hash($_POST['new_password'], PASSWORD_BCRYPT)
            ]);
        } else {
            User::update($userId, ['phone' => $phone]);
        }

        if ($profile) {
            $profData = [
                'phone' => $phone,
                'address' => $address,
                'city' => $city,
                'emergency_contact' => $emergencyContact
            ];

            if (!empty($_FILES['profile_photo']['name'])) {
                $res = Upload::file($_FILES['profile_photo'], 'uploads/reporters', 5);
                if ($res['success']) {
                    $profData['profile_photo'] = $res['file_path'];
                    User::update($userId, ['profile_image' => $res['file_path']]);
                }
            }

            ReporterProfile::update($profile['id'], $profData);
        }

        Auth::logActivity('REPORTER_PROFILE_UPDATED', 'Updated personal profile details.');
        Session::setFlash('success', 'প্রোফাইল তথ্য সফলভাবে আপডেট হয়েছে।');
        $this->redirect('/reporter/profile');
    }
}
