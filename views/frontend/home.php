<?php
/**
 * GenzNewz — Public Homepage View
 */
require_once ROOT_PATH . '/views/frontend/layouts/header.php';
?>

<!-- 1. Breaking News Ticker -->
<?php if (!empty($breakingNews)): ?>
    <section class="breaking-news-section">
        <div class="container">
            <div class="ticker-wrapper">
                <div class="ticker-badge">
                    <i class="fa-solid fa-bolt"></i> তাজা খবর
                </div>
                <div class="ticker-content">
                    <div class="ticker-marquee">
                        <?php foreach ($breakingNews as $bn): ?>
                            <div class="ticker-item">
                                <a href="/article/<?= $bn['slug'] ?>">
                                    <i class="fa-solid fa-circle" style="font-size: 6px; color: #D32F2F; vertical-align: middle; margin-right: 5px;"></i>
                                    <?= Helper::e($bn['title']) ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 2. Main Homepage Layout -->
<main class="main-content-layout">
    <div class="container">

        <!-- Top Section: Lead Story & ePaper Spotlight -->
        <div class="home-grid-top">
            <!-- Lead Featured Story -->
            <?php if ($featuredStory): ?>
                <article class="lead-story-card">
                    <div class="lead-image-wrap">
                        <a href="/article/<?= $featuredStory['slug'] ?>">
                            <img src="<?= Helper::e($featuredStory['featured_image'] ?: '/storage/uploads/articles/default_news.jpg') ?>" alt="<?= Helper::e($featuredStory['title']) ?>">
                        </a>
                    </div>
                    <div class="lead-story-body">
                        <div class="story-meta">
                            <span class="category-tag"><?= Helper::e($featuredStory['category_name']) ?></span>
                            <span><i class="fa-regular fa-user"></i> <?= Helper::e($featuredStory['author_name']) ?></span>
                            <span><i class="fa-regular fa-clock"></i> <?= Helper::timeAgo($featuredStory['published_at']) ?></span>
                        </div>
                        <h1 class="lead-story-title">
                            <a href="/article/<?= $featuredStory['slug'] ?>"><?= Helper::e($featuredStory['title']) ?></a>
                        </h1>
                        <p class="lead-story-desc">
                            <?= Helper::e($featuredStory['short_description']) ?>
                        </p>
                    </div>
                </article>
            <?php endif; ?>

            <!-- ePaper Spotlight Widget -->
            <div class="epaper-spotlight-widget">
                <div>
                    <div class="epaper-spotlight-header">
                        <h3><i class="fa-solid fa-newspaper"></i> ডিজিটাল ই-পেপার সংস্করণ</h3>
                        <p style="font-size: 0.85rem; color: #E8F5E9; margin-top: 0.2rem;">
                            <?= $todayEdition ? Helper::formatBengaliDate($todayEdition['edition_date']) : Helper::formatBengaliDate(date('Y-m-d')) ?>
                        </p>
                    </div>

                    <?php if ($todayEdition): ?>
                        <div class="epaper-preview-thumb">
                            <a href="/edition/<?= $todayEdition['slug'] ?>">
                                <img src="<?= Helper::e($todayEdition['cover_image'] ?: '/storage/pages/thumb/page_1.svg') ?>" alt="<?= Helper::e($todayEdition['title']) ?>">
                                <span class="epaper-pages-badge">
                                    <i class="fa-solid fa-layer-group"></i> <?= Helper::formatBengaliNumber($todayEdition['page_count'] ?: 8) ?> পৃষ্ঠা
                                </span>
                            </a>
                        </div>
                        <div style="font-size: 0.95rem; font-weight: 700; color: white; margin-bottom: 0.75rem;">
                            <?= Helper::e($todayEdition['title']) ?>
                        </div>
                    <?php else: ?>
                        <div style="background: rgba(0,0,0,0.2); padding: 2rem; text-align: center; border-radius: 6px; margin-bottom: 1rem;">
                            <i class="fa-solid fa-file-lines" style="font-size: 2.5rem; color: #FFD700; margin-bottom: 0.5rem;"></i>
                            <p>আজকের সংস্করণ মুদ্রণাধীন রয়েছে</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="epaper-action-buttons">
                    <?php if ($todayEdition): ?>
                        <a href="/edition/<?= $todayEdition['slug'] ?>" class="btn-read-epaper">
                            <i class="fa-solid fa-book-open"></i> সম্পূর্ণ ই-পেপার পড়ুন
                        </a>
                        <a href="/download/edition/<?= $todayEdition['slug'] ?>" class="btn-download-pdf">
                            <i class="fa-solid fa-cloud-arrow-down"></i> পিডিএফ ডাউনলোড করুন
                        </a>
                    <?php endif; ?>
                    <a href="/archive" class="btn-download-pdf" style="background: transparent; border-color: rgba(255,255,255,0.4);">
                        <i class="fa-solid fa-box-archive"></i> পুরানো সংস্করণ মহাফেজখানা
                    </a>
                </div>
            </div>
        </div>

        <!-- 3. Top Stories 4-Grid -->
        <?php if (!empty($topStories)): ?>
            <div class="section-heading-bar">
                <h2 class="section-heading-title">
                    <i class="fa-solid fa-fire" style="color: #D32F2F;"></i> প্রধান শিরোনাম ও শীর্ষ সংবাদ
                </h2>
            </div>
            <div class="stories-grid-4">
                <?php foreach ($topStories as $topArt): ?>
                    <article class="news-card">
                        <div class="news-card-img">
                            <a href="/article/<?= $topArt['slug'] ?>">
                                <img src="<?= Helper::e($topArt['featured_image'] ?: '/storage/uploads/articles/default_news.jpg') ?>" alt="<?= Helper::e($topArt['title']) ?>">
                            </a>
                        </div>
                        <div class="news-card-body">
                            <div class="story-meta">
                                <span class="category-tag"><?= Helper::e($topArt['category_name']) ?></span>
                                <span><i class="fa-regular fa-clock"></i> <?= Helper::timeAgo($topArt['published_at']) ?></span>
                            </div>
                            <h3 class="news-card-title">
                                <a href="/article/<?= $topArt['slug'] ?>"><?= Helper::e($topArt['title']) ?></a>
                            </h3>
                            <p class="news-card-desc">
                                <?= Helper::truncate(Helper::e($topArt['short_description']), 90) ?>
                            </p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- 4. Category News 3-Grid -->
        <?php if (!empty($categoryNews)): ?>
            <div class="section-heading-bar">
                <h2 class="section-heading-title">
                    <i class="fa-solid fa-cubes-stacked" style="color: var(--primary);"></i> বিভাগ ভিত্তিক সংবাদ
                </h2>
                <a href="/archive" style="font-size: 0.9rem; font-weight: 600; color: var(--primary);">সব দেখুন &rarr;</a>
            </div>
            <div class="category-blocks-grid">
                <?php foreach ($categoryNews as $catBlock): ?>
                    <div class="category-block">
                        <div class="cat-block-header">
                            <h3 class="cat-block-title"><?= Helper::e($catBlock['category']['name']) ?></h3>
                            <a href="/category/<?= $catBlock['category']['slug'] ?>" style="font-size: 0.8rem; color: var(--primary); font-weight: 600;">আরও &rarr;</a>
                        </div>
                        <ul class="cat-mini-list">
                            <?php foreach ($catBlock['articles'] as $art): ?>
                                <li class="cat-mini-item">
                                    <h4><a href="/article/<?= $art['slug'] ?>"><?= Helper::e($art['title']) ?></a></h4>
                                    <div class="cat-mini-meta">
                                        <i class="fa-regular fa-clock"></i> <?= Helper::timeAgo($art['published_at']) ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php require_once ROOT_PATH . '/views/frontend/layouts/footer.php'; ?>
