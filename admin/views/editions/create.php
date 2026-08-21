<?php
/**
 * GenzNewz — Admin Create & Edit Edition Views
 */
require_once ROOT_PATH . '/admin/views/layouts/header.php';
$isEdit = isset($edition);
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fa-solid fa-<?= $isEdit ? 'pen-to-square' : 'plus-circle' ?> text-success me-2"></i> 
                    <?= $isEdit ? 'সংস্করণ সম্পাদনা করুন' : 'নতুন ই-পেপার সংস্করণ তৈরি করুন' ?>
                </h5>
                <a href="/admin/editions" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i> তালিকায় ফিরুন</a>
            </div>
            <div class="card-body p-4">
                <form action="<?= $isEdit ? "/admin/editions/update/{$edition['id']}" : '/admin/editions/store' ?>" method="POST">
                    <?= CSRF::field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-bold">সংস্করণের শিরোনাম <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="যেমন: GenzNewz ই-পেপার — কলকাতা সংস্করণ" value="<?= Helper::e($edition['title'] ?? 'GenzNewz ই-পেপার — কলকাতা সংস্করণ') ?>" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">সংস্করণের তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="edition_date" class="form-control" value="<?= Helper::e($edition['edition_date'] ?? date('Y-m-d')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">সংস্করণ ধরণ / ক্যাটাগরি <span class="text-danger">*</span></label>
                            <select name="edition_type_id" class="form-select" required>
                                <?php foreach ($editionTypes as $et): ?>
                                    <option value="<?= $et['id'] ?>" <?= ($isEdit && $edition['edition_type_id'] == $et['id']) ? 'selected' : '' ?>>
                                        <?= Helper::e($et['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">বিবরণ / সংক্ষিপ্ত নোট</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="সংস্করণের বিষয়বস্তু বা বিশেষ সংযোজন..."><?= Helper::e($edition['description'] ?? '') ?></textarea>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">স্ট্যাটাস</label>
                            <select name="status" class="form-select">
                                <option value="published" <?= ($isEdit && $edition['status'] === 'published') ? 'selected' : '' ?>>সরাসরি প্রকাশ করুন (Published)</option>
                                <option value="draft" <?= ($isEdit && $edition['status'] === 'draft') ? 'selected' : '' ?>>খসড়া হিসেবে রাখুন (Draft)</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" <?= (!empty($edition['is_featured'])) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold text-dark" for="is_featured">
                                    হোমপেজের প্রধান স্পটলাইটে দেখান (Featured)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/admin/editions" class="btn btn-light border px-4">বাতিল</a>
                        <button type="submit" class="btn btn-success px-4" style="background: #0B6B3A; border-color: #0B6B3A;">
                            <i class="fa-solid fa-check"></i> <?= $isEdit ? 'আপডেট সংরক্ষণ করুন' : 'তৈরি করুন ও পাতা যুক্ত করুন' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/admin/views/layouts/footer.php'; ?>
