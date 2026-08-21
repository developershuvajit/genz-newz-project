<?php
/**
 * GenzNewz — Admin Articles List View
 */
require_once ROOT_PATH . '/admin/views/layouts/header.php';
?>

<div class="card card-custom">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-newspaper text-success me-2"></i> সংবাদ ও প্রতিবেদন ব্যবস্থাপনা</h5>
        </div>
        <div class="d-flex gap-2">
            <!-- Filter by status -->
            <div class="btn-group btn-group-sm">
                <a href="/admin/articles" class="btn btn-<?= ($currentStatus === 'all') ? 'success' : 'outline-secondary' ?>">সকল</a>
                <a href="/admin/articles/pending" class="btn btn-<?= ($currentStatus === 'submitted') ? 'warning' : 'outline-warning' ?>">অপেক্ষমাণ</a>
                <a href="/admin/articles/published" class="btn btn-<?= ($currentStatus === 'published') ? 'success' : 'outline-success' ?>">প্রকাশিত</a>
            </div>
            <a href="/admin/articles/create" class="btn btn-sm btn-success" style="background: #0B6B3A;"><i class="fa-solid fa-plus"></i> সংবাদ লিখুন</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">ছবি</th>
                        <th>শিরোনাম ও বিভাগ</th>
                        <th>লেখক / রিপোর্টার</th>
                        <th>ভিউ</th>
                        <th>স্ট্যাটাস</th>
                        <th>তারিখ</th>
                        <th class="text-end">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($articles['data'])): ?>
                        <tr><td colspan="7" class="text-center p-4 text-muted">কোনো প্রতিবেদন পাওয়া যায়নি।</td></tr>
                    <?php else: ?>
                        <?php foreach ($articles['data'] as $art): ?>
                            <tr>
                                <td>
                                    <img src="<?= Helper::e($art['featured_image'] ?: '/storage/uploads/articles/default_news.jpg') ?>" class="rounded border" style="width: 50px; height: 38px; object-fit: cover;" alt="Thumb">
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= Helper::truncate(Helper::e($art['title']), 55) ?></div>
                                    <div class="small text-muted">
                                        <span class="badge bg-light text-dark border"><?= Helper::e($art['category_name']) ?></span>
                                        <?php if ($art['is_breaking']): ?>
                                            <span class="badge bg-danger">ব্রেকিং</span>
                                        <?php endif; ?>
                                        <?php if ($art['is_featured']): ?>
                                            <span class="badge bg-warning text-dark">প্রধান সংবাদ</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="small fw-semibold"><?= Helper::e($art['reporter_name']) ?></div>
                                    <div class="small text-muted"><?= Helper::e($art['location'] ?? 'কলকাতা') ?></div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border"><?= Helper::formatBengaliNumber($art['views_count']) ?></span>
                                </td>
                                <td>
                                    <?php if ($art['status'] === 'published'): ?>
                                        <span class="badge bg-success">প্রকাশিত</span>
                                    <?php elseif ($art['status'] === 'submitted'): ?>
                                        <span class="badge bg-warning text-dark">রিভিউ অপেক্ষায়</span>
                                    <?php elseif ($art['status'] === 'rejected'): ?>
                                        <span class="badge bg-danger">প্রত্যাখ্যাত</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">ড্রাফট</span>
                                    <?php endif; ?>
                                </td>
                                <td class="small text-muted">
                                    <?= Helper::formatBengaliDate($art['published_at'] ?? $art['created_at']) ?>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="/admin/articles/view/<?= $art['id'] ?>" class="btn btn-outline-primary" title="পর্যালোচনা ও অনুমোদন">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="/admin/articles/edit/<?= $art['id'] ?>" class="btn btn-outline-secondary" title="সম্পাদনা">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <form action="/admin/articles/delete/<?= $art['id'] ?>" method="POST" class="d-inline confirm-delete-form">
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
