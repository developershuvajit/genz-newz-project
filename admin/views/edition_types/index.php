<?php
/**
 * GenzNewz — Admin Edition Types View
 */
require_once ROOT_PATH . '/admin/views/layouts/header.php';
?>

<div class="row g-4">
    <!-- Left: Edition Types List -->
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-tags text-success me-2"></i> ই-পেপার সংস্করণ ধরণ ও অঞ্চলসমূহ</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>সংস্করণ নাম</th>
                                <th>স্লাগ</th>
                                <th>বিবরণ</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($editionTypes as $et): ?>
                                <tr>
                                    <td class="fw-bold text-dark"><?= Helper::e($et['name']) ?></td>
                                    <td><code><?= Helper::e($et['slug']) ?></code></td>
                                    <td class="small text-muted"><?= Helper::e($et['description'] ?? '') ?></td>
                                    <td class="text-end">
                                        <form action="/admin/edition-types/delete/<?= $et['id'] ?>" method="POST" class="d-inline confirm-delete-form">
                                            <?= CSRF::field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="মুছে ফেলুন">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Add New Edition Type -->
    <div class="col-lg-4">
        <div class="card card-custom">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-plus-circle text-success me-2"></i> নতুন সংস্করণের ধরণ তৈরি করুন</h6>
            </div>
            <div class="card-body p-3">
                <form action="/admin/edition-types/store" method="POST">
                    <?= CSRF::field() ?>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">সংস্করণের ধরণ বা নাম <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="যেমন: শিলিগুড়ি ও উত্তরবঙ্গ সংস্করণ" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">ইউআরএল স্লাগ (Slug)</label>
                        <input type="text" name="slug" class="form-control" placeholder="যেমন: siliguri-edition">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">বিবরণ</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="আঞ্চলিক এলাকা বা বিশেষ কভারেজ..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100" style="background: #0B6B3A; border-color: #0B6B3A;">
                        <i class="fa-solid fa-check"></i> সংস্করণ ধরণ সংরক্ষণ করুন
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/admin/views/layouts/footer.php'; ?>
