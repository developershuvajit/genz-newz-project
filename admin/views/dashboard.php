<?php
/**
 * GenzNewz — Admin Dashboard View
 */
require_once ROOT_PATH . '/admin/views/layouts/header.php';
?>

<!-- 1. Stats Counter Cards -->
<div class="row g-3 mb-4">
    <!-- Stat 1: Today Editions -->
    <div class="col-xl-3 col-sm-6">
        <div class="card card-custom p-3 border-start border-4 border-success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">আজকের ই-পেপার সংস্করণ</div>
                    <h3 class="fw-bold my-1 text-success"><?= Helper::formatBengaliNumber($stats['today_editions']) ?></h3>
                    <div class="small text-muted">সর্বমোট: <?= Helper::formatBengaliNumber($stats['total_editions']) ?>টি সংস্করণ</div>
                </div>
                <div class="p-3 bg-success-subtle text-success rounded-circle">
                    <i class="fa-solid fa-newspaper fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 2: Pending Articles for Review -->
    <div class="col-xl-3 col-sm-6">
        <div class="card card-custom p-3 border-start border-4 border-warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">পর্যালোচনায় অপেক্ষমাণ সংবাদ</div>
                    <h3 class="fw-bold my-1 text-warning"><?= Helper::formatBengaliNumber($stats['pending_articles']) ?></h3>
                    <div class="small text-muted"><a href="/admin/articles/pending" class="text-warning fw-semibold">পর্যালোচনা করুন &rarr;</a></div>
                </div>
                <div class="p-3 bg-warning-subtle text-warning rounded-circle">
                    <i class="fa-solid fa-hourglass-half fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 3: Total Published Articles -->
    <div class="col-xl-3 col-sm-6">
        <div class="card card-custom p-3 border-start border-4 border-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">প্রকাশিত মোট সংবাদ</div>
                    <h3 class="fw-bold my-1 text-primary"><?= Helper::formatBengaliNumber($stats['published_articles']) ?></h3>
                    <div class="small text-muted">মোট পাতা: <?= Helper::formatBengaliNumber($stats['total_pages']) ?>টি</div>
                </div>
                <div class="p-3 bg-primary-subtle text-primary rounded-circle">
                    <i class="fa-solid fa-file-lines fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 4: Active Reporters -->
    <div class="col-xl-3 col-sm-6">
        <div class="card card-custom p-3 border-start border-4 border-info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">নিবন্ধিত সাংবাদিক ও রিপোর্টার</div>
                    <h3 class="fw-bold my-1 text-info"><?= Helper::formatBengaliNumber($stats['active_reporters']) ?></h3>
                    <div class="small text-muted">সর্বমোট: <?= Helper::formatBengaliNumber($stats['total_reporters']) ?> জন</div>
                </div>
                <div class="p-3 bg-info-subtle text-info rounded-circle">
                    <i class="fa-solid fa-id-card fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2. Main Dashboard Split View: Pending Review & Recent Editions -->
<div class="row g-4 mb-4">
    
    <!-- Left Col: Pending Articles Review List -->
    <div class="col-lg-7">
        <div class="card card-custom h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-clock-rotate-left text-warning me-2"></i> রিপোর্টারদের জমা দেওয়া পর্যালোচনার অপেক্ষায় সংবাদ</h6>
                <a href="/admin/articles/pending" class="btn btn-sm btn-outline-success">সকল পেন্ডিং সংবাদ</a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($pendingArticles)): ?>
                    <div class="p-4 text-center text-muted">
                        <i class="fa-solid fa-circle-check fs-2 text-success mb-2"></i>
                        <p class="mb-0">কোনো প্রতিবেদন পর্যালোচনার জন্য অপেক্ষমাণ নেই। সব কাজ সম্পন্ন!</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                            <thead class="table-light">
                                <tr>
                                    <th>শিরোনাম</th>
                                    <th>রিপোর্টার</th>
                                    <th>বিভাগ</th>
                                    <th>সময়</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pendingArticles as $pArt): ?>
                                    <tr>
                                        <td>
                                            <a href="/admin/articles/view/<?= $pArt['id'] ?>" class="fw-semibold text-dark text-decoration-none">
                                                <?= Helper::truncate(Helper::e($pArt['title']), 45) ?>
                                            </a>
                                        </td>
                                        <td><span class="badge bg-secondary"><?= Helper::e($pArt['reporter_name']) ?></span></td>
                                        <td><span class="badge bg-light text-dark border"><?= Helper::e($pArt['category_name']) ?></span></td>
                                        <td class="text-muted small"><?= Helper::timeAgo($pArt['created_at']) ?></td>
                                        <td class="text-end">
                                            <a href="/admin/articles/view/<?= $pArt['id'] ?>" class="btn btn-sm btn-primary py-1 px-2" title="পর্যালোচনা">
                                                <i class="fa-solid fa-eye"></i> রিভিউ
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Col: Recent ePaper Editions -->
    <div class="col-lg-5">
        <div class="card card-custom h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-book-open text-success me-2"></i> সাম্প্রতিক ই-পেপার সংস্করণ</h6>
                <a href="/admin/editions/create" class="btn btn-sm btn-success" style="background: #0B6B3A;"><i class="fa-solid fa-plus"></i> নতুন সংস্করণ</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php foreach ($recentEditions as $rEd): ?>
                        <div class="list-group-item p-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <img src="<?= Helper::e($rEd['cover_image'] ?: '/storage/pages/thumb/page_1.svg') ?>" class="rounded border" style="width: 48px; height: 60px; object-fit: cover;" alt="Cover">
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark"><?= Helper::e($rEd['title']) ?></h6>
                                    <div class="small text-muted"><?= Helper::formatBengaliDate($rEd['edition_date']) ?> &bull; <?= Helper::formatBengaliNumber($rEd['page_count'] ?: 8) ?> পাতা</div>
                                </div>
                            </div>
                            <div class="d-flex gap-1">
                                <a href="/admin/editions/<?= $rEd['id'] ?>/pages" class="btn btn-sm btn-outline-secondary" title="পাতা ব্যবস্থাপনা">
                                    <i class="fa-solid fa-images"></i>
                                </a>
                                <a href="/edition/<?= $rEd['slug'] ?>" target="_blank" class="btn btn-sm btn-outline-success" title="ভিউয়ারে দেখুন">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- 3. System Activity Logs -->
<div class="card card-custom">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-shield-halved text-secondary me-2"></i> সাম্প্রতিক সিস্টেম অডিট ও সিকিউরিটি লগ</h6>
        <a href="/admin/activity-logs" class="btn btn-sm btn-outline-secondary">সম্পূর্ণ লগ দেখুন</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th>ইউজার</th>
                        <th>অ্যাকশন</th>
                        <th>বিবরণ</th>
                        <th>আইপি অ্যাড্রেস</th>
                        <th>তারিখ ও সময়</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activityLogs as $log): ?>
                        <tr>
                            <td class="fw-semibold text-dark"><?= Helper::e($log['user_name'] ?? 'System') ?></td>
                            <td><span class="badge bg-dark-subtle text-dark"><?= Helper::e($log['action']) ?></span></td>
                            <td><?= Helper::e($log['details']) ?></td>
                            <td class="text-muted"><?= Helper::e($log['ip_address'] ?? '127.0.0.1') ?></td>
                            <td class="text-muted"><?= Helper::formatBengaliDate($log['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/admin/views/layouts/footer.php'; ?>
