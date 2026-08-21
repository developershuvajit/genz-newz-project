<?php
/**
 * GenzNewz — Admin Register / Edit Reporter View
 */
require_once ROOT_PATH . '/admin/views/layouts/header.php';
$isEdit = isset($profile);
?>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card card-custom">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fa-solid fa-<?= $isEdit ? 'user-pen' : 'user-plus' ?> text-success me-2"></i> 
                    <?= $isEdit ? 'সাংবাদিকের তথ্য ও অ্যাক্রেডিটেশন সম্পাদনা' : 'নতুন সাংবাদিক নিবন্ধন ও প্রেস আইডি তৈরি' ?>
                </h5>
                <a href="/admin/reporters" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i> তালিকায় ফিরুন</a>
            </div>
            <div class="card-body p-4">
                <form action="<?= $isEdit ? "/admin/reporters/update/{$profile['id']}" : '/admin/reporters/store' ?>" method="POST" enctype="multipart/form-data">
                    <?= CSRF::field() ?>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">পুরো নাম (Full Name) <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" placeholder="যেমন: রাহুল মুখার্জি" value="<?= Helper::e($profile['full_name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ইমেইল অ্যাড্রেস <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="reporter@genznewz.com" value="<?= Helper::e($profile['email'] ?? '') ?>" <?= $isEdit ? 'readonly' : 'required' ?>>
                        </div>
                    </div>

                    <?php if (!$isEdit): ?>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">লগইন পাসওয়ার্ড <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" placeholder="কমপক্ষে ৬ অক্ষরের পাসওয়ার্ড" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">পাসওয়ার্ড নিশ্চিত করুন <span class="text-danger">*</span></label>
                                <input type="password" name="confirm_password" class="form-control" placeholder="পুনরায় পাসওয়ার্ড লিখুন" required>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">পদবী (Designation) <span class="text-danger">*</span></label>
                            <input type="text" name="designation" class="form-control" placeholder="যেমন: সিনিয়র রিপোর্টার / চিফ করেসপন্ডেন্ট" value="<?= Helper::e($profile['designation'] ?? 'স্টাফ রিপোর্টার') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">অ্যাসাইনড এলাকা / ব্যুরো <span class="text-danger">*</span></label>
                            <input type="text" name="assigned_area" class="form-control" placeholder="যেমন: কলকাতা ও বিধাননগর" value="<?= Helper::e($profile['assigned_area'] ?? 'কলকাতা') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">যোগাযোগের মোবাইল নম্বর <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" placeholder="+91 98765 43210" value="<?= Helper::e($profile['phone'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">রক্তের গ্রুপ (Blood Group)</label>
                            <select name="blood_group" class="form-select">
                                <option value="A+" <?= ($isEdit && ($profile['blood_group'] ?? '') === 'A+') ? 'selected' : '' ?>>A+</option>
                                <option value="A-" <?= ($isEdit && ($profile['blood_group'] ?? '') === 'A-') ? 'selected' : '' ?>>A-</option>
                                <option value="B+" <?= ($isEdit && ($profile['blood_group'] ?? '') === 'B+') ? 'selected' : '' ?>>B+</option>
                                <option value="B-" <?= ($isEdit && ($profile['blood_group'] ?? '') === 'B-') ? 'selected' : '' ?>>B-</option>
                                <option value="O+" <?= ($isEdit && ($profile['blood_group'] ?? '') === 'O+') ? 'selected' : '' ?>>O+</option>
                                <option value="O-" <?= ($isEdit && ($profile['blood_group'] ?? '') === 'O-') ? 'selected' : '' ?>>O-</option>
                                <option value="AB+" <?= ($isEdit && ($profile['blood_group'] ?? '') === 'AB+') ? 'selected' : '' ?>>AB+</option>
                                <option value="AB-" <?= ($isEdit && ($profile['blood_group'] ?? '') === 'AB-') ? 'selected' : '' ?>>AB-</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">জরুরি যোগাযোগের ফোন নম্বর</label>
                            <input type="text" name="emergency_contact" class="form-control" placeholder="+91 98300 00000" value="<?= Helper::e($profile['emergency_contact'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">প্রেস কার্ডের বৈধতা (Valid Until) <span class="text-danger">*</span></label>
                            <input type="date" name="valid_until" class="form-control" value="<?= Helper::e($profile['valid_until'] ?? date('Y-m-d', strtotime('+1 year'))) ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">স্থায়ী ঠিকানা</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="সম্পূর্ণ ঠিকানা..."><?= Helper::e($profile['address'] ?? '') ?></textarea>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">পাসপোর্ট সাইজ প্রোফাইল ছবি</label>
                            <?php if ($isEdit && !empty($profile['profile_photo'])): ?>
                                <div class="mb-2">
                                    <img src="<?= Helper::e($profile['profile_photo']) ?>" style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover;" alt="Photo">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="profile_photo" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ডিজিটাল স্বাক্ষর (স্বচ্ছ ব্যাকগ্রাউন্ড)</label>
                            <?php if ($isEdit && !empty($profile['signature_image'])): ?>
                                <div class="mb-2">
                                    <img src="<?= Helper::e($profile['signature_image']) ?>" style="height: 40px; background: #E2E8F0; padding: 4px; border-radius: 4px;" alt="Signature">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="signature_image" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/admin/reporters" class="btn btn-light border px-4">বাতিল</a>
                        <button type="submit" class="btn btn-success px-4" style="background: #0B6B3A; border-color: #0B6B3A;">
                            <i class="fa-solid fa-check"></i> <?= $isEdit ? 'তথ্য আপডেট করুন' : 'সাংবাদিক নিবন্ধন সম্পন্ন করুন' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/admin/views/layouts/footer.php'; ?>
