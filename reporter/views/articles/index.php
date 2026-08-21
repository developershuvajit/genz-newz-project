<?php
/**
 * GenzNewz — Reporter Articles List View
 */
require_once ROOT_PATH . '/reporter/views/layouts/header.php';
?>

<div class="card card-custom">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-newspaper text-success me-2"></i> আমার সকল প্রতিবেদন</h5>
        <a href="/reporter/articles/create" class="btn btn-success" style="background: #0B6B3A;"><i class="fa-solid fa-plus"></i> নতুন সংবাদ পাঠান</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">ছবি</th>
                        <th>শিরোনাম</th>
                        <th>বিভাগ</th>
                        <th>ভিউ</th>
                        <th>স্ট্যাটাস</th>
                        <th>তারিখ</th>
                        <th class="text-end">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($articles['data'])): ?>
                        <tr><td colspan="7" class="text-center p-4 text-muted">আপনি এখনো কোনো সংবাদ প্রকাশ বা ড্রাফট করেননি।</td></tr>
                    <?php else: ?>
                        <?php foreach ($articles['data'] as $art): ?>
                            <tr>
                                <td>
                                    <img src="<?= Helper::e($art['featured_image'] ?: '/storage/uploads/articles/default_news.jpg') ?>" class="rounded border" style="width: 50px; height: 38px; object-fit: cover;" alt="Cover">
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= Helper::truncate(Helper::e($art['title']), 55) ?></div>
                                    <?php if (!empty($art['rejection_reason'])): ?>
                                        <div class="small text-danger fw-semibold"><i class="fa-solid fa-circle-exclamation"></i> প্রত্যাখ্যান কারণ: <?= Helper::e($art['rejection_reason']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= Helper::e($art['category_name']) ?></span></td>
                                <td><span class="badge bg-secondary"><?= Helper::formatBengaliNumber($art['views_count']) ?></span></td>
                                <td>
                                    <?php if ($art['status'] === 'published'): ?>
                                        <span class="badge bg-success">প্রকাশিত</span>
                                    <?php elseif ($art['status'] === 'submitted'): ?>
                                        <span class="badge bg-warning text-dark">পর্যালোচনায়</span>
                                    <?php elseif ($art['status'] === 'rejected'): ?>
                                        <span class="badge bg-danger">প্রত্যাখ্যাত</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">খসড়া</span>
                                    <?php endif; ?>
                                </td>
                                <td class="small text-muted"><?= Helper::formatBengaliDate($art['created_at']) ?></td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="/reporter/articles/view/<?= $art['id'] ?>" class="btn btn-outline-primary" title="দেখুন">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <?php if ($art['status'] !== 'published'): ?>
                                            <a href="/reporter/articles/edit/<?= $art['id'] ?>" class="btn btn-outline-secondary" title="সম্পাদনা">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <form action="/reporter/articles/delete/<?= $art['id'] ?>" method="POST" class="d-inline confirm-delete-form">
                                                <?= CSRF::field() ?>
                                                <button type="submit" class="btn btn-outline-danger" title="মুছে ফেলুন">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
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

<?php require_once ROOT_PATH . '/reporter/views/layouts/footer.php'; ?>
