<?php
/**
 * GenzNewz — Admin Create & Edit Article View
 */
require_once ROOT_PATH . '/admin/views/layouts/header.php';
$isEdit = isset($article);
?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-custom">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fa-solid fa-<?= $isEdit ? 'pen-to-square' : 'plus-circle' ?> text-success me-2"></i> 
                    <?= $isEdit ? 'প্রতিবেদন সম্পাদনা করুন' : 'নতুন সংবাদ / প্রতিবেদন প্রকাশ করুন' ?>
                </h5>
                <a href="/admin/articles" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i> তালিকায় ফিরুন</a>
            </div>
            <div class="card-body p-4">
                <form action="<?= $isEdit ? "/admin/articles/update/{$article['id']}" : '/admin/articles/store' ?>" method="POST" enctype="multipart/form-data">
                    <?= CSRF::field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-bold">সংবাদের প্রধান শিরোনাম <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-lg" placeholder="আকর্ষণীয় ও তথ্যবহুল শিরোনাম লিখুন..." value="<?= Helper::e($article['title'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">উপ-শিরোনাম (Sub-headline)</label>
                        <input type="text" name="subheadline" class="form-control" placeholder="সংবাদের সংক্ষিপ্ত সারমর্ম..." value="<?= Helper::e($article['subheadline'] ?? '') ?>">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">সংবাদ বিভাগ / ক্যাটাগরি <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= ($isEdit && $article['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                        <?= Helper::e($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">সংবাদের স্থান / ব্যুরো</label>
                            <input type="text" name="location" class="form-control" placeholder="যেমন: কলকাতা, নবান্ন, শিলিগুড়ি..." value="<?= Helper::e($article['location'] ?? 'কলকাতা') ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">ফিচার্ড ছবি / প্রধান ছবি</label>
                        <?php if ($isEdit && !empty($article['featured_image'])): ?>
                            <div class="mb-2">
                                <img src="<?= Helper::e($article['featured_image']) ?>" style="height: 100px; border-radius: 6px;" alt="Current Image">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="featured_image" class="form-control" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">সংক্ষিপ্ত সূচনা (Intro / Lead)</label>
                        <textarea name="short_description" class="form-control" rows="2" placeholder="হোমপেজ ও প্রিভিউ কার্ডের জন্য প্রথম ২-৩ লাইন..."><?= Helper::e($article['short_description'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">সম্পূর্ণ সংবাদ প্রতিবেদন (Content) <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control" rows="10" placeholder="সম্পূর্ণ বিস্তারিত প্রতিবেদন লিখুন..." required><?= Helper::e($article['content'] ?? '') ?></textarea>
                    </div>

                    <div class="row g-3 mb-4 p-3 bg-light rounded border">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">প্রকাশনা স্ট্যাটাস</label>
                            <select name="status" class="form-select">
                                <option value="published" <?= ($isEdit && $article['status'] === 'published') ? 'selected' : '' ?>>সরাসরি প্রকাশ করুন (Published)</option>
                                <option value="draft" <?= ($isEdit && $article['status'] === 'draft') ? 'selected' : '' ?>>খসড়া (Draft)</option>
                                <option value="submitted" <?= ($isEdit && $article['status'] === 'submitted') ? 'selected' : '' ?>>পর্যালোচনার জন্য অপেক্ষমাণ (Submitted)</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-center">
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="is_breaking" id="is_breaking" value="1" <?= (!empty($article['is_breaking'])) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold text-danger" for="is_breaking">
                                    <i class="fa-solid fa-bolt"></i> ব্রেকিং নিউজ টিকারে দেখান
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-center">
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" <?= (!empty($article['is_featured'])) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold text-success" for="is_featured">
                                    <i class="fa-solid fa-star"></i> প্রধান শিরোনাম (Lead Story)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/admin/articles" class="btn btn-light border px-4">বাতিল</a>
                        <button type="submit" class="btn btn-success px-4" style="background: #0B6B3A; border-color: #0B6B3A;">
                            <i class="fa-solid fa-check"></i> <?= $isEdit ? 'আপডেট সংরক্ষণ করুন' : 'সংবাদ প্রকাশ করুন' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/admin/views/layouts/footer.php'; ?>
