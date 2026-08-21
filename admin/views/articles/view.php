<?php
/**
 * GenzNewz — Admin Review Article View
 */
require_once ROOT_PATH . '/admin/views/layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-9">
        
        <!-- Action Banner for Review -->
        <div class="card card-custom mb-4 border-start border-4 border-<?= ($article['status'] === 'published') ? 'success' : (($article['status'] === 'submitted') ? 'warning' : 'secondary') ?>">
            <div class="card-body p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <span class="badge bg-<?= ($article['status'] === 'published') ? 'success' : (($article['status'] === 'submitted') ? 'warning text-dark' : 'danger') ?> fs-6">
                        স্ট্যাটাস: <?= ($article['status'] === 'published') ? 'প্রকাশিত' : (($article['status'] === 'submitted') ? 'পর্যালোচনার জন্য অপেক্ষমাণ' : 'প্রত্যাখ্যাত') ?>
                    </span>
                    <span class="text-muted ms-2 small">লেখক: <strong><?= Helper::e($article['reporter_name']) ?></strong> &bull; জমা দেওয়া হয়েছে: <?= Helper::formatBengaliDate($article['created_at']) ?></span>
                </div>

                <div class="d-flex gap-2">
                    <?php if ($article['status'] !== 'published'): ?>
                        <form action="/admin/articles/approve/<?= $article['id'] ?>" method="POST">
                            <?= CSRF::field() ?>
                            <button type="submit" class="btn btn-success" style="background: #0B6B3A;">
                                <i class="fa-solid fa-circle-check"></i> অনুমোদন ও প্রকাশ করুন
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if ($article['status'] !== 'rejected'): ?>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fa-solid fa-circle-xmark"></i> প্রত্যাখ্যান করুন
                        </button>
                    <?php endif; ?>

                    <a href="/admin/articles/edit/<?= $article['id'] ?>" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-pen"></i> সম্পাদনা
                    </a>
                </div>
            </div>
        </div>

        <?php if (!empty($article['rejection_reason'])): ?>
            <div class="alert alert-danger mb-4">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> <strong>পূর্ববর্তী প্রত্যাখ্যানের কারণ:</strong> <?= Helper::e($article['rejection_reason']) ?>
            </div>
        <?php endif; ?>

        <!-- Full Article Reading Preview -->
        <div class="card card-custom mb-4">
            <div class="card-body p-5">
                <span class="badge bg-light text-dark border mb-2"><?= Helper::e($article['category_name']) ?></span>
                
                <h1 class="fw-bold text-dark mb-2" style="font-family: 'Noto Serif Bengali', serif;"><?= Helper::e($article['title']) ?></h1>
                
                <?php if (!empty($article['subheadline'])): ?>
                    <h4 class="text-muted mb-3"><?= Helper::e($article['subheadline']) ?></h4>
                <?php endif; ?>

                <div class="d-flex align-items-center gap-3 py-3 my-3 border-top border-bottom">
                    <img src="<?= Helper::e($article['reporter_photo'] ?: '/storage/uploads/reporters/default_reporter.jpg') ?>" class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover;" alt="Author">
                    <div>
                        <div class="fw-bold text-dark"><?= Helper::e($article['reporter_name']) ?></div>
                        <div class="small text-muted"><?= Helper::e($article['reporter_designation'] ?? 'স্টাফ রিপোর্টার') ?> &bull; স্থান: <?= Helper::e($article['location'] ?? 'কলকাতা') ?></div>
                    </div>
                </div>

                <?php if (!empty($article['featured_image'])): ?>
                    <div class="mb-4 text-center">
                        <img src="<?= Helper::e($article['featured_image']) ?>" class="img-fluid rounded border" alt="Featured">
                    </div>
                <?php endif; ?>

                <?php if (!empty($article['short_description'])): ?>
                    <div class="p-3 bg-light border-start border-4 border-success rounded mb-4 fw-semibold text-secondary">
                        <?= Helper::e($article['short_description']) ?>
                    </div>
                <?php endif; ?>

                <div class="article-body-text" style="font-size: 1.05rem; line-height: 1.8; color: #334155;">
                    <?= nl2br(Helper::e($article['content'])) ?>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal: Rejection Reason -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/admin/articles/reject/<?= $article['id'] ?>" method="POST">
                <?= CSRF::field() ?>
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-danger" id="rejectModalLabel">
                        <i class="fa-solid fa-circle-exclamation me-2"></i> প্রতিবেদন প্রত্যাখ্যানের কারণ
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted">রিপোর্টারকে জানান কেন এই সংবাদটি প্রত্যাখ্যাত হলো (সংশোধনের নির্দেশ বা নীতিগত কারণ):</p>
                    <textarea name="rejection_reason" class="form-control" rows="4" placeholder="যেমন: তথ্যের যথার্থতা নিশ্চিত নয় / শিরোনাম সংশোধন প্রয়োজন..." required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-danger">প্রত্যাখ্যান নিশ্চিত করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/admin/views/layouts/footer.php'; ?>
