<?php
/**
 * GenzNewz — Admin Notifications View
 */
require_once ROOT_PATH . '/admin/views/layouts/header.php';
?>

<div class="card card-custom">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-bell text-warning me-2"></i> সিস্টেম ও সম্পাদকীয় বিজ্ঞপ্তিসমূহ</h5>
        <form action="/admin/notifications/mark-all-read" method="POST" class="d-inline">
            <?= CSRF::field() ?>
            <button type="submit" class="btn btn-sm btn-outline-success">
                <i class="fa-solid fa-check-double me-1"></i> সব পঠিত হিসেবে চিহ্নিত করুন
            </button>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            <?php if (empty($notifications)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="fa-regular fa-bell-slash fs-1 mb-2"></i>
                    <p class="mb-0">কোনো বিজ্ঞপ্তি নেই।</p>
                </div>
            <?php else: ?>
                <?php foreach ($notifications as $notif): ?>
                    <div class="list-group-item p-3 d-flex justify-content-between align-items-center <?= empty($notif['is_read']) ? 'bg-light' : '' ?>">
                        <div class="d-flex gap-3 align-items-center">
                            <div class="p-2 rounded-circle bg-<?= empty($notif['is_read']) ? 'warning' : 'secondary' ?>-subtle text-<?= empty($notif['is_read']) ? 'warning' : 'secondary' ?>">
                                <i class="fa-solid fa-circle-info"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark"><?= Helper::e($notif['title']) ?></h6>
                                <p class="mb-0 text-muted small"><?= Helper::e($notif['message']) ?></p>
                                <div class="text-muted small" style="font-size: 0.75rem;"><?= Helper::timeAgo($notif['created_at']) ?></div>
                            </div>
                        </div>
                        <?php if (!empty($notif['link'])): ?>
                            <a href="<?= Helper::e($notif['link']) ?>" class="btn btn-sm btn-outline-primary">দেখুন &rarr;</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/admin/views/layouts/footer.php'; ?>
