<?php
/**
 * GenzNewz — Reporter Dashboard View
 */
require_once ROOT_PATH . '/reporter/views/layouts/header.php';
$isExpired = strtotime($profile['valid_until']) < time();
?>

<!-- 1. Top Press Accreditation Verification Card -->
<div class="card card-custom mb-4 border-start border-4 border-<?= ($profile['id_card_status'] === 'active' && !$isExpired) ? 'success' : 'danger' ?>">
    <div class="card-body p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 rounded bg-<?= ($profile['id_card_status'] === 'active' && !$isExpired) ? 'success' : 'danger' ?>-subtle text-<?= ($profile['id_card_status'] === 'active' && !$isExpired) ? 'success' : 'danger' ?> fs-4">
                <i class="fa-solid fa-id-badge"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold text-dark">ডিজিটাল প্রেস অ্যাক্রেডিটেশন: <?= Helper::e($profile['reporter_id']) ?></h6>
                <div class="small text-muted">
                    পদবী: <strong><?= Helper::e($profile['designation']) ?></strong> &bull; ব্যুরো: <strong><?= Helper::e($profile['assigned_area']) ?></strong> &bull; মেয়াদ: <?= Helper::formatBengaliDate($profile['valid_until']) ?>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="/reporter/id-card" class="btn btn-sm btn-outline-success">
                <i class="fa-solid fa-id-card me-1"></i> প্রেস আইডি কার্ড দেখুন ও প্রিন্ট করুন
            </a>
            <a href="/reporter/articles/create" class="btn btn-sm btn-success" style="background: #0B6B3A;">
                <i class="fa-solid fa-pen-nib me-1"></i> নতুন সংবাদ পাঠান
            </a>
        </div>
    </div>
</div>

<!-- 2. Statistics Counter Grid -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="card card-custom p-3 border-start border-4 border-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">মোট জমা দেওয়া সংবাদ</div>
                    <h3 class="fw-bold my-1 text-primary"><?= Helper::formatBengaliNumber($stats['total_articles']) ?></h3>
                </div>
                <div class="p-3 bg-primary-subtle text-primary rounded-circle">
                    <i class="fa-solid fa-file-lines fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card card-custom p-3 border-start border-4 border-success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">অনুমোদিত ও প্রকাশিত</div>
                    <h3 class="fw-bold my-1 text-success"><?= Helper::formatBengaliNumber($stats['published_articles']) ?></h3>
                </div>
                <div class="p-3 bg-success-subtle text-success rounded-circle">
                    <i class="fa-solid fa-circle-check fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card card-custom p-3 border-start border-4 border-warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">পর্যালোচনায় অপেক্ষমাণ</div>
                    <h3 class="fw-bold my-1 text-warning"><?= Helper::formatBengaliNumber($stats['pending_articles']) ?></h3>
                </div>
                <div class="p-3 bg-warning-subtle text-warning rounded-circle">
                    <i class="fa-solid fa-hourglass-half fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card card-custom p-3 border-start border-4 border-info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">সংবাদে মোট পাঠক ভিউ</div>
                    <h3 class="fw-bold my-1 text-info"><?= Helper::formatBengaliNumber($stats['total_views']) ?></h3>
                </div>
                <div class="p-3 bg-info-subtle text-info rounded-circle">
                    <i class="fa-solid fa-eye fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3. Recent Articles List -->
<div class="card card-custom">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-newspaper text-success me-2"></i> আমার সাম্প্রতিক প্রতিবেদনসমূহ</h6>
        <a href="/reporter/articles" class="btn btn-sm btn-outline-secondary">সকল প্রতিবেদন দেখুন</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>শিরোনাম</th>
                        <th>বিভাগ</th>
                        <th>ভিউ</th>
                        <th>স্ট্যাটাস</th>
                        <th>তারিখ</th>
                        <th class="text-end">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentArticles)): ?>
                        <tr><td colspan="6" class="text-center p-4 text-muted">আপনি এখনো কোনো সংবাদ জমা দেননি।</td></tr>
                    <?php else: ?>
                        <?php foreach ($recentArticles as $art): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark"><?= Helper::truncate(Helper::e($art['title']), 55) ?></div>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= Helper::e($art['category_name']) ?></span></td>
                                <td><span class="badge bg-secondary"><?= Helper::formatBengaliNumber($art['views_count']) ?></span></td>
                                <td>
                                    <?php if ($art['status'] === 'published'): ?>
                                        <span class="badge bg-success">প্রকাশিত</span>
                                    <?php elseif ($art['status'] === 'submitted'): ?>
                                        <span class="badge bg-warning text-dark">পর্যালোচনায়</span>
                                    <?php elseif ($art['status'] === 'rejected'): ?>
                                        <span class="badge bg-danger" title="<?= Helper::e($art['rejection_reason'] ?? '') ?>">প্রত্যাখ্যাত</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">খসড়া</span>
                                    <?php endif; ?>
                                </td>
                                <td class="small text-muted"><?= Helper::formatBengaliDate($art['created_at']) ?></td>
                                <td class="text-end">
                                    <a href="/reporter/articles/view/<?= $art['id'] ?>" class="btn btn-sm btn-outline-primary py-1 px-2" title="দেখুন">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <?php if ($art['status'] !== 'published'): ?>
                                        <a href="/reporter/articles/edit/<?= $art['id'] ?>" class="btn btn-sm btn-outline-secondary py-1 px-2" title="সম্পাদনা">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                    <?php endif; ?>
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
