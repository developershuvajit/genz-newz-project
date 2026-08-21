<?php
/**
 * GenzNewz — Admin Settings View
 */
require_once ROOT_PATH . '/admin/views/layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-custom">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-gear text-success me-2"></i> পোর্টাল ও সিস্টেম সেটিংস</h5>
            </div>
            <div class="card-body p-4">
                <form action="/admin/settings/update" method="POST" enctype="multipart/form-data">
                    <?= CSRF::field() ?>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">পত্রিকার নাম (Site Name)</label>
                            <input type="text" name="site_name" class="form-control" value="<?= Helper::e($settings['site_name'] ?? 'GenzNewz') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ব্রাউজার টাইটেল (Site Title)</label>
                            <input type="text" name="site_title" class="form-control" value="<?= Helper::e($settings['site_title'] ?? 'GenzNewz — ডিজিটাল বাংলা সংবাদপত্র ও ই-পেপার') ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">স্লোগান / ট্যাগলাইন (Tagline)</label>
                        <input type="text" name="site_tagline" class="form-control" value="<?= Helper::e($settings['site_tagline'] ?? 'সত্যের সন্ধানে অবিচল — সার্বক্ষণিক নির্ভরযোগ্য ডিজিটাল সংবাদপত্র ও ই-পেপার সংস্করণ') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">এসইও ডেসক্রিপশন (Meta Description)</label>
                        <textarea name="seo_description" class="form-control" rows="2"><?= Helper::e($settings['seo_description'] ?? 'GenzNewz — আধুনিক ডিজিটাল বাংলা সংবাদপত্র ও প্রিমিয়াম ই-পেপার পোর্টাল') ?></textarea>
                    </div>

                    <h6 class="fw-bold text-success border-bottom pb-2 mt-4 mb-3"><i class="fa-solid fa-address-book me-1"></i> সম্পাদকীয় ও যোগাযোগ তথ্য</h6>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">অফিসিয়াল ইমেইল</label>
                            <input type="email" name="contact_email" class="form-control" value="<?= Helper::e($settings['contact_email'] ?? 'editor@genznewz.com') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">হেল্পলাইন / ফোন</label>
                            <input type="text" name="contact_phone" class="form-control" value="<?= Helper::e($settings['contact_phone'] ?? '+91 33 2248 0000') ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">প্রধান কার্যালয়ের ঠিকানা</label>
                        <input type="text" name="contact_address" class="form-control" value="<?= Helper::e($settings['contact_address'] ?? '১২/এ, আনন্দবাজার লেন, বি.বি.ডি বাগ, কলকাতা — ৭০০০০১') ?>">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">ফুটার কপিরাইট টেক্সট</label>
                        <input type="text" name="footer_text" class="form-control" value="<?= Helper::e($settings['footer_text'] ?? '© ২০২৬ GenzNewz Digital Media Pvt Ltd. সর্বস্বত্ব সংরক্ষিত।') ?>">
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success px-4" style="background: #0B6B3A; border-color: #0B6B3A;">
                            <i class="fa-solid fa-floppy-disk me-1"></i> সেটিংস সংরক্ষণ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/admin/views/layouts/footer.php'; ?>
