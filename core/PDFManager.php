<?php
/**
 * GenzNewz — PDF Download & Stream Manager
 */

class PDFManager {
    public static function streamEditionPdf(array $edition): void {
        if ($edition['status'] !== 'published' && !Auth::isAdmin()) {
            http_response_code(403);
            die('এই সংস্করণের পিডিএফ ডাউনলোড বর্তমানে উপলব্ধ নয়।');
        }

        $pdfPath = $edition['pdf_file'] ?? null;
        $title = Helper::slugify($edition['title']) . '.pdf';

        if ($pdfPath && file_exists(ROOT_PATH . '/' . ltrim($pdfPath, '/'))) {
            $fullPath = ROOT_PATH . '/' . ltrim($pdfPath, '/');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $title . '"');
            header('Content-Length: ' . filesize($fullPath));
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');
            readfile($fullPath);
            exit;
        }

        // If specific physical PDF is not yet compiled, generate dynamic HTML-PDF stream response
        header('Content-Type: text/html; charset=utf-8');
        echo <<<HTML
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>GenzNewz — PDF সংস্করণ: {$edition['title']}</title>
    <style>
        body { font-family: 'Noto Sans Bengali', sans-serif; text-align: center; padding: 50px; background: #F3F4F6; }
        .card { background: white; max-width: 600px; margin: 0 auto; padding: 40px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .btn { display: inline-block; background: #0B6B3A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
        .btn:hover { background: #064D2B; }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="color: #0B6B3A;">GenzNewz ই-পেপার ডিজিটাল সংস্করণ</h2>
        <p><strong>{$edition['title']}</strong></p>
        <p>আজকের সংস্করণের সম্পূর্ণ পিডিএফ প্রিন্ট রেডি করা হচ্ছে। আপনি অনলাইন হাই-ডেফিনিশন ই-পেপার ভিউয়ারে সম্পূর্ণ সংস্করণটি পড়তে পারবেন।</p>
        <a href="/edition/{$edition['slug']}" class="btn">ই-পেপার ভিউয়ারে পড়ুন</a>
        <br><br>
        <button onclick="window.print()" class="btn" style="background:#19A463;">প্রিন্ট সংস্করণ / সেভ অ্যাজ PDF</button>
    </div>
</body>
</html>
HTML;
        exit;
    }
}
