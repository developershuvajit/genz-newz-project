<?php
/**
 * GenzNewz — Reporter Profile Settings View
 */
require_once ROOT_PATH . '/reporter/views/layouts/header.php';
$user = Auth::user();
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-user-gear text-success me-2"></i> প্রোফাইল সেটিংস ও পাসওয়ার্ড পরিবর্তন</h5>
            </div>
            <div class="card-body p-4">
                <form action="/reporter/profile/update" method="POST" enctype="multipart/form-data">
                    <?= CSRF::field() ?>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">পুরো নাম</label>
                            <input type="text" name="name" class="form-control" value="<?= Helper::e($profile['full_name'] ?? $user['name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ইমেইল</label>
                            <input type="email" class="form-control" value="<?= Helper::e($user['email']) ?>" readonly>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ফোন নম্বর</label>
                            <input type="text" name="phone" class="form-control" value="<?= Helper::e($profile['phone'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">জরুরি যোগাযোগ</label>
                            <input type="text" name="emergency_contact" class="form-control" value="<?= Helper::e($profile['emergency_contact'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">ঠিকানা</label>
                        <textarea name="address" class="form-control" rows="2"><?= Helper::e($profile['address'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">প্রোফাইল ছবি পরিবর্তন</label>
                        <input type="file" name="profile_photo" class="form-control" accept="image/*">
                    </div>

                    <h6 class="fw-bold text-danger border-bottom pb-2 mt-4 mb-3"><i class="fa-solid fa-key me-1"></i> পাসওয়ার্ড পরিবর্তন (ঐচ্ছিক)</h6>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">নতুন পাসওয়ার্ড</label>
                            <input type="password" name="new_password" class="form-control" placeholder="অপরিবর্তিত রাখতে ফাঁকা রাখুন">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">পাসওয়ার্ড নিশ্চিত করুন</label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="পুনরায় নতুন পাসওয়ার্ড লিখুন">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success px-4" style="background: #0B6B3A; border-color: #0B6B3A;">
                            <i class="fa-solid fa-check me-1"></i> প্রোফাইল তথ্য সংরক্ষণ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/reporter/views/layouts/footer.php'; ?>
