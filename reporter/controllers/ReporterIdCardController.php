<?php
/**
 * GenzNewz — Reporter ID Card Controller
 */

declare(strict_types=1);

class ReporterIdCardController extends Controller {
    public function index(): void {
        Auth::requireReporter();

        $userId = Auth::id();
        $user = Auth::user();
        $profile = ReporterProfile::findByUserId($userId);

        if (!$profile) {
            $this->error404('রিপোর্টার প্রোফাইল বা প্রেস কার্ডের তথ্য পাওয়া যায়নি।');
            return;
        }

        $this->view('reporter/views/id_card/index', [
            'pageTitle' => 'আমার অফিসিয়াল প্রেস আইডি কার্ড',
            'user' => $user,
            'profile' => $profile
        ]);
    }
}
