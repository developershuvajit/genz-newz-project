<?php
/**
 * GenzNewz — Admin Database Backup & Restore View
 */
require_once ROOT_PATH . '/admin/views/layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-database text-success me-2"></i> ডেটাবেস ব্যাকআপ ও রিস্টোর</h5>
            </div>
            <div class="card-body p-4 text-center">
                <div class="p-4 bg-light rounded border mb-4">
                    <i class="fa-solid fa-server fs-1 text-success mb-3"></i>
                    <h5 class="fw-bold">সম্পূর্ণ এসকিউএল (SQL) ডেটাবেস ব্যাকআপ ডাউনলোড করুন</h5>
                    <p class="text-muted small mb-3">সকল ই-পেপার সংস্করণ, পৃষ্ঠা, প্রকাশিত সংবাদ, সাংবাদিক প্রোফাইল ও সেটিংসের তাৎক্ষণিক ব্যাকআপ ফাইল তৈরি করতে নিচের বোতামে ক্লিক করুন।</p>
                    
                    <form action="/admin/backup/create" method="POST">
                        <?= CSRF::field() ?>
                        <button type="submit" class="btn btn-success px-4 py-2" style="background: #0B6B3A; border-color: #0B6B3A;">
                            <i class="fa-solid fa-download me-2"></i> ব্যাকআপ SQL ফাইল তৈরি ও ডাউনলোড
                        </button>
                    </form>
                </div>

                <div class="text-muted small text-start">
                    <i class="fa-solid fa-circle-info text-info me-1"></i> <strong>পরামর্শ:</strong> নিয়মিত সার্ভার ব্যাকআপ সংরক্ষণ করুন। কোনো অপ্রত্যাশিত ত্রুটি বা ডাটা মাইগ্রেশনের সময় এই SQL ফাইলটি সরাসরি MySQL এ ইমপোর্ট করা যাবে।
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/admin/views/layouts/footer.php'; ?>
