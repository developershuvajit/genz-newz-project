<?php
/**
 * GenzNewz — Admin Editions List View
 */
require_once ROOT_PATH . '/admin/views/layouts/header.php';
?>

<div class="card card-custom">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-book-open text-success me-2"></i> ই-পেপার সংস্করণ তালিকা</h5>
        <a href="/admin/editions/create" class="btn btn-success" style="background: #0B6B3A;"><i class="fa-solid fa-plus"></i> নতুন সংস্করণ প্রকাশ করুন</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">কভার</th>
                        <th>সংস্করণ নাম</th>
                        <th>ধরণ</th>
                        <th>তারিখ</th>
                        <th>মোট পাতা</th>
                        <th>স্ট্যাটাস</th>
                        <th class="text-end">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($editions['data'])): ?>
                        <tr><td colspan="7" class="text-center p-4 text-muted">কোনো সংস্করণ তৈরি করা হয়নি।</td></tr>
                    <?php else: ?>
                        <?php foreach ($editions['data'] as $ed): ?>
                            <tr>
                                <td>
                                    <img src="<?= Helper::e($ed['cover_image'] ?: '/storage/pages/thumb/page_1.svg') ?>" class="rounded border" style="width: 45px; height: 55px; object-fit: cover;" alt="Cover">
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= Helper::e($ed['title']) ?></div>
                                    <div class="small text-muted"><?= Helper::e($ed['slug']) ?></div>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= Helper::e($ed['edition_type_name']) ?></span></td>
                                <td><?= Helper::formatBengaliDate($ed['edition_date']) ?></td>
                                <td>
                                    <span class="badge bg-secondary"><?= Helper::formatBengaliNumber($ed['page_count']) ?> পাতা</span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= ($ed['status'] === 'published') ? 'success' : 'secondary' ?>">
                                        <?= ($ed['status'] === 'published') ? 'প্রকাশিত' : 'ড্রাফট' ?>
                                    </span>
                                    <?php if ($ed['is_featured']): ?>
                                        <span class="badge bg-warning text-dark">আজকের বিশেষ</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="/admin/editions/<?= $ed['id'] ?>/pages" class="btn btn-outline-primary" title="পাতা পরিচালনা">
                                            <i class="fa-solid fa-images"></i> পাতা
                                        </a>
                                        <a href="/edition/<?= $ed['slug'] ?>" target="_blank" class="btn btn-outline-info" title="পাবলিক ভিউয়ার">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="/admin/editions/edit/<?= $ed['id'] ?>" class="btn btn-outline-secondary" title="সম্পাদনা">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <form action="/admin/editions/delete/<?= $ed['id'] ?>" method="POST" class="d-inline confirm-delete-form">
                                            <?= CSRF::field() ?>
                                            <button type="submit" class="btn btn-outline-danger" title="মুছে ফেলুন">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/admin/views/layouts/footer.php'; ?>
