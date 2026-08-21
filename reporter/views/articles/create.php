<?php
/**
 * GenzNewz — Reporter Create & Edit Article View
 */
require_once ROOT_PATH . '/reporter/views/layouts/header.php';
$isEdit = isset($article);
?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-custom">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fa-solid fa-<?= $isEdit ? 'pen-to-square' : 'feather-pointed' ?> text-success me-2"></i> 
                    <?= $isEdit ? 'প্রতিবেদন সংশোধন করুন' : 'নতুন সংবাদ লিখুন ও সম্পাদকীয় দপ্তরে পাঠান' ?>
                </h5>
                <a href="/reporter/articles" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i> তালিকায় ফিরুন</a>
            </div>
            <div class="card-body p-4">

                <?php if ($isEdit && !empty($article['rejection_reason'])): ?>
                    <div class="alert alert-danger mb-4">
                        <h6 class="fw-bold"><i class="fa-solid fa-triangle-exclamation me-1"></i> সম্পাদকীয় দপ্তরের পর্যালোচনার মন্তব্য:</h6>
                        <p class="mb-0"><?= Helper::e($article['rejection_reason']) ?></p>
                        <small class="text-muted">অনুগ্রহ করে নিচের ফর্মটিতে প্রয়োজনীয় সংশোধন করে পুনরায় "পর্যালোচনার জন্য পাঠান" বোতামে ক্লিক করুন।</small>
                    </div>
                <?php endif; ?>

                <form action="<?= $isEdit ? "/reporter/articles/update/{$article['id']}" : '/reporter/articles/store' ?>" method="POST" enctype="multipart/form-data">
                    <?= CSRF::field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-bold">সংবাদের প্রধান শিরোনাম <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-lg" placeholder="তথ্যবহুল ও স্পষ্ট শিরোনাম দিন..." value="<?= Helper::e($article['title'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">উপ-শিরোনাম (Sub-headline)</label>
                        <input type="text" name="subheadline" class="form-control" placeholder="সংক্ষিপ্ত সারমর্ম..." value="<?= Helper::e($article['subheadline'] ?? '') ?>">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">বিভাগ নির্বাচন করুন <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= ($isEdit && $article['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                        <?= Helper::e($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">সংবাদের স্থান / লোকেশন</label>
                            <input type="text" name="location" class="form-control" placeholder="যেমন: নবান্ন, শিয়ালদহ..." value="<?= Helper::e($article['location'] ?? 'কলকাতা') ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">ফিচার্ড ছবি</label>
                        <?php if ($isEdit && !empty($article['featured_image'])): ?>
                            <div class="mb-2">
                                <img src="<?= Helper::e($article['featured_image']) ?>" style="height: 90px; border-radius: 6px;" alt="Image">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="featured_image" class="form-control" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">সংক্ষিপ্ত সূচনা (Intro)</label>
                        <textarea name="short_description" class="form-control" rows="2" placeholder="প্রথম অনুচ্ছেদের সংক্ষিপ্ত রূপ..."><?= Helper::e($article['short_description'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">সম্পূর্ণ বিস্তারিত সংবাদ (Content) <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control" rows="10" placeholder="সম্পূর্ণ বিস্তারিত প্রতিবেদন লিখুন..." required><?= Helper::e($article['content'] ?? '') ?></textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" name="status" value="draft" class="btn btn-outline-secondary px-4">
                            <i class="fa-regular fa-floppy-disk me-1"></i> খসড়া হিসেবে সংরক্ষণ (Save Draft)
                        </button>
                        <button type="submit" name="status" value="submitted" class="btn btn-success px-4 py-2" style="background: #0B6B3A; border-color: #0B6B3A;">
                            <i class="fa-solid fa-paper-plane me-1"></i> সম্পাদকীয় দপ্তরে পর্যালোচনার জন্য জমা দিন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/reporter/views/layouts/footer.php'; ?>
