<?php
/**
 * GenzNewz — Admin Profile Settings View
 */
require_once ROOT_PATH . '/admin/views/layouts/header.php';
$user = Auth::user();
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-user-gear text-success me-2"></i> অ্যাডমিন প্রোফাইল ও পাসওয়ার্ড পরিবর্তন</h5>
            </div>
            <div class="card-body p-4">
                <form action="/admin/profile/update" method="POST" enctype="multipart/form-data">
                    <?= CSRF::field() ?>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">অ্যাডমিনের পুরো নাম <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="<?= Helper::e($user['name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ইমেইল অ্যাড্রেস <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="<?= Helper::e($user['email']) ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">প্রোফাইল ছবি</label>
                        <input type="file" name="profile_image" class="form-control" accept="image/*">
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
                            <i class="fa-solid fa-check me-1"></i> তথ্য সংরক্ষণ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/admin/views/layouts/footer.php'; ?>
