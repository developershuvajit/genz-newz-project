<?php
/**
 * GenzNewz — Public Single Article View
 */
require_once ROOT_PATH . '/views/frontend/layouts/header.php';
?>

<main class="main-content-layout">
    <div class="container">
        
        <div class="article-page-layout">
            
            <!-- Left: Main Article Content -->
            <article class="article-main-container">
                
                <!-- Category Tag & Meta -->
                <div style="margin-bottom: 0.75rem;">
                    <a href="/category/<?= $article['category_slug'] ?>" class="category-tag">
                        <?= Helper::e($article['category_name']) ?>
                    </a>
                    <?php if (!empty($article['location'])): ?>
                        <span style="font-size: 0.85rem; color: var(--dark-muted); margin-left: 0.5rem;">
                            <i class="fa-solid fa-location-dot"></i> <?= Helper::e($article['location']) ?>
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Headline -->
                <h1 class="article-headline"><?= Helper::e($article['title']) ?></h1>

                <!-- Subheadline -->
                <?php if (!empty($article['subheadline'])): ?>
                    <h2 class="article-subheadline"><?= Helper::e($article['subheadline']) ?></h2>
                <?php endif; ?>

                <!-- Author Strip & Social Shares -->
                <div class="article-author-strip">
                    <div class="author-meta-info">
                        <img src="<?= Helper::e($article['reporter_photo'] ?: '/storage/uploads/reporters/default_reporter.jpg') ?>" alt="<?= Helper::e($article['author_name']) ?>" class="author-avatar-img">
                        <div class="author-names">
                            <h5><?= Helper::e($article['author_name']) ?></h5>
                            <p>
                                <?= Helper::e($article['reporter_designation'] ?? 'স্টাফ রিপোর্টার') ?> 
                                <?php if (!empty($article['reporter_id'])): ?>
                                    &bull; <a href="/reporter/verify/<?= $article['reporter_id'] ?>" style="color: var(--primary); text-decoration: underline;" target="_blank">প্রেস আইডি: <?= $article['reporter_id'] ?></a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="font-size: 0.82rem; color: var(--dark-muted); text-align: right;">
                            <div>প্রকাশিত: <?= Helper::formatBengaliDate($article['published_at'] ?? $article['created_at']) ?></div>
                            <div><i class="fa-regular fa-eye"></i> <?= Helper::formatBengaliNumber($article['views_count'] ?? 1) ?> বার পঠিত</div>
                        </div>

                        <!-- Share Buttons -->
                        <div class="article-share-links">
                            <a href="#" class="share-btn share-fb btn-share-trigger" data-platform="facebook" title="ফেসবুকে শেয়ার করুন"><i class="fa-brands fa-facebook-f"></i></a>
                            <a href="#" class="share-btn share-tw btn-share-trigger" data-platform="twitter" title="টুইটারে শেয়ার করুন"><i class="fa-brands fa-x-twitter"></i></a>
                            <a href="#" class="share-btn share-wa btn-share-trigger" data-platform="whatsapp" title="হোয়াটসঅ্যাপে পাঠান"><i class="fa-brands fa-whatsapp"></i></a>
                            <button type="button" class="share-btn share-print" onclick="window.print()" title="প্রিন্ট করুন"><i class="fa-solid fa-print"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Featured Image -->
                <?php if (!empty($article['featured_image'])): ?>
                    <div class="article-featured-media">
                        <img src="<?= Helper::e($article['featured_image']) ?>" alt="<?= Helper::e($article['title']) ?>">
                    </div>
                <?php endif; ?>

                <!-- Article Content Body -->
                <div class="article-content-body">
                    <?php if (!empty($article['short_description'])): ?>
                        <div style="font-size: 1.15rem; font-weight: 600; color: #1E293B; line-height: 1.7; margin-bottom: 1.5rem; border-left: 3px solid var(--primary); padding-left: 1rem;">
                            <?= Helper::e($article['short_description']) ?>
                        </div>
                    <?php endif; ?>

                    <?= nl2br(Helper::e($article['content'])) ?>
                </div>

                <!-- Article Footer / Accreditation Stamp -->
                <div style="margin-top: 2.5rem; padding: 1.25rem; background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 6px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <div style="font-weight: 700; color: var(--primary-dark); font-size: 0.95rem;">
                            <i class="fa-solid fa-certificate" style="color: #0B6B3A;"></i> GenzNewz ডিজিটাল ভেরিফাইড নিউজ ডেস্ক
                        </div>
                        <div style="font-size: 0.8rem; color: var(--dark-muted);">
                            প্রতিবেদন সম্পর্কিত কোনো তথ্য বা মন্তব্যের জন্য editorial@genznewz.com-এ যোগাযোগ করুন।
                        </div>
                    </div>
                    <?php if (!empty($article['reporter_id'])): ?>
                        <a href="/reporter/verify/<?= $article['reporter_id'] ?>" class="btn-filter-submit" style="font-size: 0.85rem; padding: 0.4rem 0.85rem; text-decoration: none;" target="_blank">
                            <i class="fa-solid fa-id-badge"></i> সাংবাদিকের প্রেস আইডি যাচাই
                        </a>
                    <?php endif; ?>
                </div>

            </article>

            <!-- Right Sidebar: Trending & Related News -->
            <aside>
                
                <!-- Related News in Category -->
                <?php if (!empty($relatedArticles)): ?>
                    <div style="background: white; border-radius: 8px; border: 1px solid var(--border-color); padding: 1.25rem; margin-bottom: 1.5rem;">
                        <h3 style="font-family: var(--font-heading); font-size: 1.2rem; border-bottom: 2px solid var(--primary); padding-bottom: 0.4rem; margin-bottom: 1rem; color: var(--primary-dark);">
                            <i class="fa-solid fa-link"></i> একই বিভাগের আরও সংবাদ
                        </h3>
                        <ul class="cat-mini-list">
                            <?php foreach ($relatedArticles as $relArt): ?>
                                <li class="cat-mini-item">
                                    <h4><a href="/article/<?= $relArt['slug'] ?>"><?= Helper::e($relArt['title']) ?></a></h4>
                                    <div class="cat-mini-meta">
                                        <i class="fa-regular fa-clock"></i> <?= Helper::timeAgo($relArt['published_at']) ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Trending Stories -->
                <?php if (!empty($trendingArticles)): ?>
                    <div style="background: white; border-radius: 8px; border: 1px solid var(--border-color); padding: 1.25rem; margin-bottom: 1.5rem;">
                        <h3 style="font-family: var(--font-heading); font-size: 1.2rem; border-bottom: 2px solid var(--accent-red); padding-bottom: 0.4rem; margin-bottom: 1rem; color: var(--accent-red);">
                            <i class="fa-solid fa-arrow-trend-up"></i> সর্বাধিক পঠিত
                        </h3>
                        <ul class="cat-mini-list">
                            <?php foreach ($trendingArticles as $tIdx => $tArt): ?>
                                <li class="cat-mini-item" style="display: flex; gap: 0.75rem; align-items: flex-start;">
                                    <span style="font-size: 1.4rem; font-weight: 900; color: #CBD5E1; line-height: 1;">
                                        <?= Helper::formatBengaliNumber($tIdx + 1) ?>
                                    </span>
                                    <div>
                                        <h4><a href="/article/<?= $tArt['slug'] ?>"><?= Helper::e($tArt['title']) ?></a></h4>
                                        <div class="cat-mini-meta">
                                            <i class="fa-regular fa-eye"></i> <?= Helper::formatBengaliNumber($tArt['views_count'] ?? 1) ?> ভিউ
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Today ePaper Ad Card -->
                <div style="background: linear-gradient(135deg, #0B6B3A, #064D2B); color: white; padding: 1.5rem; border-radius: 8px; text-align: center;">
                    <i class="fa-solid fa-newspaper" style="font-size: 2.5rem; color: #FFD700; margin-bottom: 0.75rem;"></i>
                    <h4 style="font-family: var(--font-heading); font-size: 1.2rem; margin-bottom: 0.5rem; color: #FFD700;">আজকের ই-পেপার পড়ুন</h4>
                    <p style="font-size: 0.85rem; color: #E8F5E9; margin-bottom: 1rem;">মুদ্রিত কাগজের মতো আসল লেআউটে হাই-রেজোলিউশন অনলাইন সংস্করণ।</p>
                    <a href="/archive" class="btn-read-epaper" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                        <i class="fa-solid fa-book-open"></i> ই-পেপার খুলুন
                    </a>
                </div>

            </aside>

        </div>

    </div>
</main>

<?php require_once ROOT_PATH . '/views/frontend/layouts/footer.php'; ?>
