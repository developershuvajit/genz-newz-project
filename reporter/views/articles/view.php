<?php
/**
 * GenzNewz — Reporter View Single Article
 */
require_once ROOT_PATH . '/reporter/views/layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-9">
        
        <div class="card card-custom mb-4 border-start border-4 border-<?= ($article['status'] === 'published') ? 'success' : (($article['status'] === 'submitted') ? 'warning' : 'danger') ?>">
            <div class="card-body p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <span class="badge bg-<?= ($article['status'] === 'published') ? 'success' : (($article['status'] === 'submitted') ? 'warning text-dark' : 'danger') ?> fs-6">
                        বর্তমান অবস্থা: <?= ($article['status'] === 'published') ? 'অনুমোদিত ও প্রকাশিত' : (($article['status'] === 'submitted') ? 'সম্পাদকীয় পর্যালোচনায়' : 'প্রত্যাখ্যাত / সংশোধন প্রয়োজন') ?>
                    </span>
                    <span class="text-muted ms-2 small">জমা দেওয়া হয়েছে: <?= Helper::formatBengaliDate($article['created_at']) ?></span>
                </div>

                <div class="d-flex gap-2">
                    <?php if ($article['status'] !== 'published'): ?>
                        <a href="/reporter/articles/edit/<?= $article['id'] ?>" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-pen"></i> সংশোধন ও সম্পাদনা
                        </a>
                    <?php else: ?>
                        <a href="/article/<?= $article['slug'] ?>" target="_blank" class="btn btn-success" style="background: #0B6B3A;">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i> লাইভ সাইটে পড়ুন
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if (!empty($article['rejection_reason'])): ?>
            <div class="alert alert-danger mb-4">
                <i class="fa-solid fa-circle-exclamation me-2"></i> <strong>সম্পাদকীয় দপ্তরের মন্তব্য:</strong> <?= Helper::e($article['rejection_reason']) ?>
            </div>
        <?php endif; ?>

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
                        <div class="small text-muted"><?= Helper::e($article['location'] ?? 'কলকাতা') ?> &bull; ভিউ: <?= Helper::formatBengaliNumber($article['views_count'] ?? 0) ?></div>
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

<?php require_once ROOT_PATH . '/reporter/views/layouts/footer.php'; ?>
