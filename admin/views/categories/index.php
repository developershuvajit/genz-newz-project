<?php
/**
 * GenzNewz — Admin Categories Management View
 */
require_once ROOT_PATH . '/admin/views/layouts/header.php';
?>

<div class="row g-4">
    <!-- Left: Categories Table -->
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-layer-group text-success me-2"></i> সকল সংবাদ বিভাগ / ক্যাটাগরি</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>নাম (বাংলা)</th>
                                <th>নাম (ইংরেজি)</th>
                                <th>স্লাগ</th>
                                <th>ক্রম</th>
                                <th>স্ট্যাটাস</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td class="fw-bold text-dark"><?= Helper::e($cat['name']) ?></td>
                                    <td class="text-muted"><?= Helper::e($cat['name_en'] ?? '') ?></td>
                                    <td><code><?= Helper::e($cat['slug']) ?></code></td>
                                    <td><?= Helper::formatBengaliNumber($cat['order_index'] ?? 0) ?></td>
                                    <td>
                                        <span class="badge bg-<?= ($cat['status'] === 'active') ? 'success' : 'secondary' ?>">
                                            <?= ($cat['status'] === 'active') ? 'সক্রিয়' : 'নিষ্ক্রিয়' ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <form action="/admin/categories/delete/<?= $cat['id'] ?>" method="POST" class="d-inline confirm-delete-form">
                                            <?= CSRF::field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="মুছে ফেলুন">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Add New Category Form -->
    <div class="col-lg-4">
        <div class="card card-custom">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-plus-circle text-success me-2"></i> নতুন বিভাগ যুক্ত করুন</h6>
            </div>
            <div class="card-body p-3">
                <form action="/admin/categories/store" method="POST">
                    <?= CSRF::field() ?>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">বিভাগের নাম (বাংলা) <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="যেমন: স্বাস্থ্য ও জীবন" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">নাম (ইংরেজি)</label>
                        <input type="text" name="name_en" class="form-control" placeholder="যেমন: Health & Life">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">ইউআরএল স্লাগ (Slug)</label>
                        <input type="text" name="slug" class="form-control" placeholder="স্বয়ংক্রিয়ভাবে তৈরি হবে...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">প্রদর্শনের ক্রম (Order Index)</label>
                        <input type="number" name="order_index" class="form-control" value="0">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">স্ট্যাটাস</label>
                        <select name="status" class="form-select">
                            <option value="active">সক্রিয় (Active)</option>
                            <option value="inactive">নিষ্ক্রিয় (Inactive)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success w-100" style="background: #0B6B3A; border-color: #0B6B3A;">
                        <i class="fa-solid fa-check"></i> ক্যাটাগরি সংরক্ষণ করুন
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/admin/views/layouts/footer.php'; ?>
