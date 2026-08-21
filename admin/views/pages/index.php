<?php
/**
 * GenzNewz — Admin Pages Management View
 */
require_once ROOT_PATH . '/admin/views/layouts/header.php';
?>

<div class="card card-custom mb-4">
    <div class="card-body p-3">
        <div class="row align-items-center g-3">
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted mb-1">সংস্করণ পরিবর্তন করুন:</label>
                <select class="form-select" onchange="window.location.href='/admin/editions/' + this.value + '/pages'">
                    <option value="">-- সংস্করণ নির্বাচন করুন --</option>
                    <?php foreach ($allEditions as $edOption): ?>
                        <option value="<?= $edOption['id'] ?>" <?= ($selectedEdition && $selectedEdition['id'] == $edOption['id']) ? 'selected' : '' ?>>
                            <?= Helper::e($edOption['title']) ?> (<?= Helper::formatBengaliDate($edOption['edition_date']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 text-md-end">
                <?php if ($selectedEdition): ?>
                    <a href="/admin/pages/upload?edition_id=<?= $selectedEdition['id'] ?>" class="btn btn-success" style="background: #0B6B3A;">
                        <i class="fa-solid fa-cloud-arrow-up"></i> একাধিক পাতা বাল্ক আপলোড করুন
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if ($selectedEdition): ?>
    <div class="card card-custom">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-file-image text-success me-2"></i> <?= Helper::e($selectedEdition['title']) ?> — পাতাসমূহ</h5>
                <div class="small text-muted"><?= Helper::formatBengaliDate($selectedEdition['edition_date']) ?> &bull; মোট <?= Helper::formatBengaliNumber(count($pages)) ?>টি পাতা যুক্ত আছে</div>
            </div>
            <a href="/edition/<?= $selectedEdition['slug'] ?>" target="_blank" class="btn btn-sm btn-outline-success">
                <i class="fa-solid fa-book-open"></i> ই-পেপার প্রিভিউ
            </a>
        </div>
        <div class="card-body p-4">
            <?php if (empty($pages)): ?>
                <div class="text-center py-5">
                    <i class="fa-solid fa-images fs-1 text-muted mb-3"></i>
                    <h5>এই সংস্করণে এখনো কোনো পাতা আপলোড করা হয়নি</h5>
                    <p class="text-muted small">মুদ্রিত পাতার ছবি বা PDF আপলোড করতে নিচের বোতামে ক্লিক করুন।</p>
                    <a href="/admin/pages/upload?edition_id=<?= $selectedEdition['id'] ?>" class="btn btn-success" style="background: #0B6B3A;">
                        <i class="fa-solid fa-upload"></i> পাতা আপলোড শুরু করুন
                    </a>
                </div>
            <?php else: ?>
                <div class="row g-4" id="sortable-pages-grid">
                    <?php foreach ($pages as $p): ?>
                        <div class="col-xl-3 col-lg-4 col-sm-6 page-card-item" data-id="<?= $p['id'] ?>">
                            <div class="card h-100 border shadow-sm position-relative">
                                <div class="position-absolute top-0 start-0 m-2 badge bg-dark opacity-75">
                                    পাতা <?= Helper::formatBengaliNumber($p['page_number']) ?>
                                </div>
                                <img src="<?= Helper::e($p['thumbnail'] ?: $p['page_image']) ?>" class="card-img-top" style="height: 280px; object-fit: cover; background: #E2E8F0;" alt="পাতা">
                                <div class="card-body p-2 d-flex justify-content-between align-items-center bg-light">
                                    <div class="fw-bold small text-truncate" style="max-width: 130px;"><?= Helper::e($p['page_title'] ?? "পৃষ্ঠা {$p['page_number']}") ?></div>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= Helper::e($p['page_image']) ?>" target="_blank" class="btn btn-outline-secondary" title="বড় আকারে দেখুন">
                                            <i class="fa-solid fa-expand"></i>
                                        </a>
                                        <form action="/admin/pages/delete/<?= $p['id'] ?>" method="POST" class="d-inline confirm-delete-form">
                                            <?= CSRF::field() ?>
                                            <button type="submit" class="btn btn-outline-danger" title="পাতা মুছে ফেলুন">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <div class="card card-custom text-center p-5">
        <i class="fa-solid fa-book-open-reader fs-1 text-muted mb-3"></i>
        <h5>অনুগ্রহ করে উপরে থেকে একটি সংস্করণ নির্বাচন করুন</h5>
    </div>
<?php endif; ?>

<?php require_once ROOT_PATH . '/admin/views/layouts/footer.php'; ?>
