<?php
/**
 * GenzNewz — Admin Activity & Audit Logs View
 */
require_once ROOT_PATH . '/admin/views/layouts/header.php';
?>

<div class="card card-custom">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-shield-halved text-success me-2"></i> সিস্টেম অডিট ও সিকিউরিটি অ্যাক্টিভিটি লগ</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">আইডি</th>
                        <th>ইউজার</th>
                        <th>অ্যাকশন</th>
                        <th>বিবরণ ও মডিউল</th>
                        <th>আইপি ঠিকানা</th>
                        <th>তারিখ ও সময়</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs['data'])): ?>
                        <tr><td colspan="6" class="text-center p-4 text-muted">কোনো লগ পাওয়া যায়নি।</td></tr>
                    <?php else: ?>
                        <?php foreach ($logs['data'] as $l): ?>
                            <tr>
                                <td class="text-muted small">#<?= $l['id'] ?></td>
                                <td class="fw-bold text-dark"><?= Helper::e($l['user_name'] ?? 'System / Anonymous') ?></td>
                                <td>
                                    <span class="badge bg-dark-subtle text-dark"><?= Helper::e($l['action']) ?></span>
                                </td>
                                <td class="small"><?= Helper::e($l['details']) ?></td>
                                <td class="text-muted small font-monospace"><?= Helper::e($l['ip_address'] ?? '127.0.0.1') ?></td>
                                <td class="text-muted small"><?= Helper::formatBengaliDate($l['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/admin/views/layouts/footer.php'; ?>
