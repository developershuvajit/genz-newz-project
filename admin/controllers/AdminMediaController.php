<?php
/**
 * GenzNewz — Admin Media Controller
 */

declare(strict_types=1);

class AdminMediaController extends Controller {
    public function index(): void {
        Auth::requireAdmin();
        $media = MediaLibrary::all('id DESC', 30);

        $this->view('admin/views/media/index', [
            'pageTitle' => 'মিডিয়া লাইব্রেরি',
            'media' => $media
        ]);
    }
}
