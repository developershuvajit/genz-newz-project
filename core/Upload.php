<?php
/**
 * GenzNewz — Secure File Upload Manager
 */

class Upload {
    private static array $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
    private static array $bannedExtensions = ['php', 'php3', 'php4', 'php5', 'phtml', 'phar', 'exe', 'cgi', 'js', 'sh', 'pl', 'py'];
    private static array $allowedMimeTypes = [
        'image/jpeg', 'image/png', 'image/webp', 'image/jpg', 'image/svg+xml',
        'application/pdf'
    ];

    public static function file(array $file, string $subDirectory = 'uploads', int $maxSizeMb = 25): array {
        if (!isset($file['error']) || is_array($file['error'])) {
            return ['success' => false, 'error' => 'অবৈধ ফাইল আপলোড অনুরোধ।'];
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => self::getErrorMessage($file['error'])];
        }

        if ($file['size'] > $maxSizeMb * 1024 * 1024) {
            return ['success' => false, 'error' => "ফাইলের আকার {$maxSizeMb}MB-এর চেয়ে বেশি হতে পারবে না।"];
        }

        $origName = $file['name'];
        $extension = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

        if (in_array($extension, self::$bannedExtensions, true)) {
            return ['success' => false, 'error' => 'নিরাপত্তাজনিত কারণে এই ধরণের ফাইল আপলোড নিষিদ্ধ।'];
        }

        if (!in_array($extension, self::$allowedExtensions, true) && $extension !== 'svg') {
            return ['success' => false, 'error' => 'শুধুমাত্র JPG, PNG, WEBP এবং PDF ফাইল অনুমোদিত।'];
        }

        // Validate MIME type via finfo
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!in_array($mime, self::$allowedMimeTypes, true) && !str_starts_with($mime, 'image/')) {
            return ['success' => false, 'error' => 'অবৈধ ফাইল ফরম্যাট বা করাপ্ট ফাইল।'];
        }

        // Generate safe random filename
        $randomName = bin2hex(random_bytes(16)) . '_' . time() . '.' . $extension;
        $targetDir = STORAGE_PATH . '/' . trim($subDirectory, '/');
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $targetPath = $targetDir . '/' . $randomName;
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => false, 'error' => 'সার্ভারে ফাইল স্থানান্তর করতে ব্যর্থ হয়েছে।'];
        }

        $storageUrl = '/storage/' . trim($subDirectory, '/') . '/' . $randomName;

        return [
            'success' => true,
            'file_name' => $randomName,
            'original_name' => $origName,
            'file_path' => $storageUrl,
            'full_path' => $targetPath,
            'file_size' => $file['size'],
            'extension' => $extension,
            'mime_type' => $mime
        ];
    }

    private static function getErrorMessage(int $code): string {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'ফাইলের আকার অনুমোদিত সীমার চেয়ে বড়।',
            UPLOAD_ERR_PARTIAL => 'ফাইলটি আংশিক আপলোড হয়েছে। আবার চেষ্টা করুন।',
            UPLOAD_ERR_NO_FILE => 'কোনো ফাইল নির্বাচন করা হয়নি।',
            UPLOAD_ERR_NO_TMP_DIR => 'সার্ভারের টেম্প ডিরেক্টরি অনুপস্থিত।',
            UPLOAD_ERR_CANT_WRITE => 'ডিস্কে ফাইল লিখতে ব্যর্থ হয়েছে।',
            default => 'একটি অপ্রত্যাশিত আপলোড ত্রুটি ঘটেছে।'
        };
    }
}
