<?php
/**
 * GenzNewz — Admin Reporters List View
 */
require_once ROOT_PATH . '/admin/views/layouts/header.php';
?>

<div class="card card-custom">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-id-card text-success me-2"></i> প্রেস অ্যাক্রেডিটেশন ও সাংবাদিক তালিকা</h5>
        <a href="/admin/reporters/create" class="btn btn-success" style="background: #0B6B3A;"><i class="fa-solid fa-user-plus"></i> নতুন সাংবাদিক নিবন্ধন করুন</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">ছবি</th>
                        <th>নাম ও পদবী</th>
                        <th>প্রেস আইডি ও কোড</th>
                        <th>অ্যাসাইনড এলাকা</th>
                        <th>কার্ড স্ট্যাটাস</th>
                        <th>বৈধতার মেয়াদ</th>
                        <th class="text-end">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reporters['data'])): ?>
                        <tr><td colspan="7" class="text-center p-4 text-muted">কোনো সাংবাদিক নিবন্ধিত নেই।</td></tr>
                    <?php else: ?>
                        <?php foreach ($reporters['data'] as $rpt): ?>
                            <?php $isExpired = strtotime($rpt['valid_until']) < time(); ?>
                            <tr>
                                <td>
                                    <img src="<?= Helper::e($rpt['profile_photo'] ?: '/storage/uploads/reporters/default_reporter.jpg') ?>" class="rounded-circle border" style="width: 44px; height: 44px; object-fit: cover;" alt="Photo">
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= Helper::e($rpt['full_name']) ?></div>
                                    <div class="small text-muted"><?= Helper::e($rpt['designation'] ?? 'স্টাফ রিপোর্টার') ?></div>
                                </td>
                                <td>
                                    <span class="badge bg-dark-subtle text-dark font-monospace"><?= Helper::e($rpt['reporter_id']) ?></span>
                                    <div class="small text-muted"><?= Helper::e($rpt['employee_code'] ?? 'N/A') ?></div>
                                </td>
                                <td><?= Helper::e($rpt['assigned_area']) ?></td>
                                <td>
                                    <span class="badge bg-<?= ($rpt['id_card_status'] === 'active' && !$isExpired) ? 'success' : 'danger' ?>">
                                        <?= ($rpt['id_card_status'] === 'active' && !$isExpired) ? 'সক্রিয়' : ($isExpired ? 'মেয়াদোত্তীর্ণ' : 'স্থগিত') ?>
                                    </span>
                                </td>
                                <td class="small">
                                    <?= Helper::formatBengaliDate($rpt['valid_until']) ?>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="/admin/reporters/id-card/<?= $rpt['id'] ?>" class="btn btn-outline-success" title="প্রেস আইডি কার্ড তৈরি ও প্রিন্ট">
                                            <i class="fa-solid fa-id-badge"></i> আইডি কার্ড
                                        </a>
                                        <a href="/admin/reporters/view/<?= $rpt['id'] ?>" class="btn btn-outline-primary" title="প্রোফাইল দেখুন">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="/admin/reporters/edit/<?= $rpt['id'] ?>" class="btn btn-outline-secondary" title="তথ্য সম্পাদনা">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <form action="/admin/reporters/delete/<?= $rpt['id'] ?>" method="POST" class="d-inline confirm-delete-form">
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
