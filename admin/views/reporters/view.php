<?php
/**
 * GenzNewz — Admin View Reporter Profile
 */
require_once ROOT_PATH . '/admin/views/layouts/header.php';
$isExpired = strtotime($profile['valid_until']) < time();
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-user-tie text-success me-2"></i> সাংবাদিক প্রোফাইল বিবরণ</h5>
                <div class="d-flex gap-2">
                    <a href="/admin/reporters/id-card/<?= $profile['id'] ?>" class="btn btn-sm btn-success" style="background: #0B6B3A;"><i class="fa-solid fa-id-badge"></i> আইডি কার্ড</a>
                    <a href="/admin/reporters/edit/<?= $profile['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-pen"></i> সম্পাদনা</a>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-4 mb-4 pb-4 border-bottom">
                    <img src="<?= Helper::e($profile['profile_photo'] ?: '/storage/uploads/reporters/default_reporter.jpg') ?>" class="rounded-circle border" style="width: 90px; height: 90px; object-fit: cover;" alt="Avatar">
                    <div>
                        <h4 class="fw-bold mb-1"><?= Helper::e($profile['full_name']) ?></h4>
                        <div class="text-success fw-bold"><?= Helper::e($profile['designation']) ?></div>
                        <div class="text-muted small">প্রেস আইডি: <strong><?= Helper::e($profile['reporter_id']) ?></strong> | এমপ্লয়ি কোড: <strong><?= Helper::e($profile['employee_code'] ?? 'N/A') ?></strong></div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="text-muted small">ইমেইল:</label>
                        <div class="fw-bold"><?= Helper::e($profile['email']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">ফোন নম্বর:</label>
                        <div class="fw-bold"><?= Helper::e($profile['phone']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">অ্যাসাইনড এলাকা / ব্যুরো:</label>
                        <div class="fw-bold"><?= Helper::e($profile['assigned_area']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">রক্তের গ্রুপ:</label>
                        <div class="fw-bold"><?= Helper::e($profile['blood_group'] ?? 'N/A') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">জরুরি যোগাযোগ:</label>
                        <div class="fw-bold"><?= Helper::e($profile['emergency_contact'] ?? 'N/A') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">প্রেস কার্ডের মেয়াদ:</label>
                        <div class="fw-bold text-<?= $isExpired ? 'danger' : 'success' ?>"><?= Helper::formatBengaliDate($profile['valid_until']) ?></div>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small">ঠিকানা:</label>
                        <div class="fw-bold"><?= Helper::e($profile['address'] ?? 'N/A') ?></div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="/admin/reporters" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i> তালিকায় ফিরুন</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/admin/views/layouts/footer.php'; ?>
