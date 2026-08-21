<?php
/**
 * GenzNewz — Base Controller Class
 */

abstract class Controller {
    protected function view(string $viewPath, array $data = []): void {
        extract($data);

        $fullPath = ROOT_PATH . '/views/' . $viewPath . '.php';
        if (str_starts_with($viewPath, 'admin/')) {
            $fullPath = ROOT_PATH . '/' . $viewPath . '.php';
        } elseif (str_starts_with($viewPath, 'reporter/')) {
            $fullPath = ROOT_PATH . '/' . $viewPath . '.php';
        }

        if (!file_exists($fullPath)) {
            $this->error500("View file [{$viewPath}] not found at {$fullPath}");
            return;
        }

        // Global variables available in all views
        $currentUser = Auth::user();
        $siteTitle = Helper::getSetting('site_title', APP_TITLE);
        $siteName = Helper::getSetting('site_name', APP_NAME);
        $siteTagline = Helper::getSetting('site_tagline', APP_TAGLINE);

        require $fullPath;
    }

    protected function json(mixed $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    protected function redirect(string $url): void {
        header("Location: {$url}");
        exit;
    }

    protected function back(): void {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: {$referer}");
        exit;
    }

    public function error404(string $message = 'পৃষ্ঠাটি খুঁজে পাওয়া যায়নি'): void {
        http_response_code(404);
        $this->view('frontend/404', [
            'pageTitle' => '৪০৪ — পৃষ্ঠা পাওয়া যায়নি',
            'message' => $message
        ]);
        exit;
    }

    public function error403(string $message = 'অ্যাক্সেস অস্বীকৃত (অনুমতি নেই)'): void {
        http_response_code(403);
        $this->view('frontend/403', [
            'pageTitle' => '৪০৩ — অ্যাক্সেস অস্বীকৃত',
            'message' => $message
        ]);
        exit;
    }

    public function error500(string $message = 'সার্ভার ত্রুটি ঘটেছে'): void {
        http_response_code(500);
        $this->view('frontend/500', [
            'pageTitle' => '৫০০ — অভ্যন্তরীণ সার্ভার ত্রুটি',
            'message' => $message
        ]);
        exit;
    }
}
