<?php
/**
 * GenzNewz — Admin Media Library View
 */
require_once ROOT_PATH . '/admin/views/layouts/header.php';
?>

<div class="card card-custom mb-4">
    <div class="card-body p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-photo-film text-success me-2"></i> মিডিয়া ও ফাইল লাইব্রেরি</h5>
        
        <!-- Upload Media Form -->
        <form action="/admin/media/upload" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
            <?= CSRF::field() ?>
            <input type="file" name="file" class="form-control form-control-sm" required accept="image/*,.pdf">
            <button type="submit" class="btn btn-sm btn-success text-nowrap" style="background: #0B6B3A;">
                <i class="fa-solid fa-cloud-arrow-up"></i> আপলোড করুন
            </button>
        </form>
    </div>
</div>

<div class="card card-custom">
    <div class="card-body p-4">
        <?php if (empty($media['data'])): ?>
            <div class="text-center py-5 text-muted">
                <i class="fa-solid fa-images fs-1 mb-2"></i>
                <p>কোনো মিডিয়া ফাইল আপলোড করা হয়নি।</p>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($media['data'] as $item): ?>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="card h-100 border shadow-sm">
                            <div style="height: 120px; overflow: hidden; background: #F1F5F9; display: flex; align-items: center; justify-content: center;">
                                <?php if (str_starts_with($item['mime_type'], 'image/')): ?>
                                    <img src="<?= Helper::e($item['file_path']) ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="Media">
                                <?php else: ?>
                                    <i class="fa-solid fa-file-pdf fs-1 text-danger"></i>
                                <?php endif; ?>
                            </div>
                            <div class="card-body p-2">
                                <div class="small fw-bold text-truncate" title="<?= Helper::e($item['file_name']) ?>"><?= Helper::e($item['file_name']) ?></div>
                                <div class="small text-muted" style="font-size: 0.75rem;"><?= round($item['file_size'] / 1024, 1) ?> KB</div>
                            </div>
                            <div class="card-footer p-2 bg-light d-flex justify-content-between">
                                <button type="button" class="btn btn-xs btn-outline-secondary p-1" style="font-size: 0.75rem;" onclick="navigator.clipboard.writeText('<?= Helper::e($item['file_path']) ?>'); Swal.fire({toast:true, position:'top-end', icon:'success', title:'ইউআরএল কপি করা হয়েছে', showConfirmButton:false, timer:1500});">
                                    <i class="fa-solid fa-copy"></i> কপি
                                </button>
                                <form action="/admin/media/delete/<?= $item['id'] ?>" method="POST" class="d-inline confirm-delete-form">
                                    <?= CSRF::field() ?>
                                    <button type="submit" class="btn btn-xs btn-outline-danger p-1" style="font-size: 0.75rem;" title="মুছে ফেলুন">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once ROOT_PATH . '/admin/views/layouts/footer.php'; ?>
