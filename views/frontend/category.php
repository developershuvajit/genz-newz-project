<?php
/**
 * GenzNewz — Public Category News Archive View
 */
require_once ROOT_PATH . '/views/frontend/layouts/header.php';
?>

<main class="main-content-layout">
    <div class="container">
        
        <div class="section-heading-bar">
            <h1 class="section-heading-title">
                <i class="fa-solid fa-folder-open" style="color: var(--primary);"></i> <?= Helper::e($category['name']) ?> (<?= Helper::e($category['name_en'] ?? '') ?>)
            </h1>
            <span style="font-size: 0.85rem; color: var(--dark-muted);">মোট <?= Helper::formatBengaliNumber($total) ?>টি সংবাদ</span>
        </div>

        <?php if (!empty($articles)): ?>
            <div style="display: grid; grid-template-columns: 2.3fr 1fr; gap: 2rem;">
                
                <!-- Main Articles List -->
                <div>
                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <?php foreach ($articles as $art): ?>
                            <article style="background: white; border-radius: 8px; border: 1px solid var(--border-color); overflow: hidden; display: flex; gap: 1.25rem; box-shadow: var(--shadow-sm); padding: 1rem;">
                                <?php if (!empty($art['featured_image'])): ?>
                                    <div style="width: 220px; height: 140px; flex-shrink: 0; border-radius: 6px; overflow: hidden; background: #F1F5F9;">
                                        <a href="/article/<?= $art['slug'] ?>">
                                            <img src="<?= Helper::e($art['featured_image']) ?>" alt="<?= Helper::e($art['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <div style="display: flex; flex-direction: column; justify-content: space-between; flex-grow: 1;">
                                    <div>
                                        <div style="font-size: 0.8rem; color: var(--dark-muted); margin-bottom: 0.35rem;">
                                            <span><i class="fa-regular fa-user"></i> <?= Helper::e($art['reporter_name']) ?></span> &bull; 
                                            <span><i class="fa-regular fa-clock"></i> <?= Helper::timeAgo($art['published_at'] ?? $art['created_at']) ?></span>
                                        </div>
                                        <h2 style="font-family: var(--font-heading); font-size: 1.25rem; font-weight: 700; line-height: 1.4; margin-bottom: 0.5rem;">
                                            <a href="/article/<?= $art['slug'] ?>"><?= Helper::e($art['title']) ?></a>
                                        </h2>
                                        <p style="font-size: 0.9rem; color: #475569; line-height: 1.5;">
                                            <?= Helper::truncate(Helper::e($art['short_description']), 140) ?>
                                        </p>
                                    </div>
                                    <div style="margin-top: 0.5rem;">
                                        <a href="/article/<?= $art['slug'] ?>" style="font-size: 0.85rem; font-weight: 700; color: var(--primary);">সম্পূর্ণ পড়ুন &rarr;</a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 2rem;">
                            <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                                <a href="?page=<?= $p ?>" 
                                   style="padding: 0.5rem 1rem; border-radius: 4px; border: 1px solid var(--border-color); background: <?= ($p == $current_page) ? 'var(--primary)' : 'white' ?>; color: <?= ($p == $current_page) ? 'white' : 'var(--dark)' ?>; font-weight: bold;">
                                    <?= Helper::formatBengaliNumber($p) ?>
                                </a>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Sidebar Categories -->
                <aside>
                    <div style="background: white; border-radius: 8px; border: 1px solid var(--border-color); padding: 1.25rem;">
                        <h3 style="font-family: var(--font-heading); font-size: 1.2rem; border-bottom: 2px solid var(--primary); padding-bottom: 0.4rem; margin-bottom: 1rem; color: var(--primary-dark);">
                            <i class="fa-solid fa-list"></i> অন্যান্য বিভাগসমূহ
                        </h3>
                        <ul class="footer-links-list">
                            <?php foreach ($allCategories as $c): ?>
                                <li style="padding: 0.4rem 0; border-bottom: 1px solid var(--border-color);">
                                    <a href="/category/<?= $c['slug'] ?>" style="color: <?= ($c['slug'] === $category['slug']) ? 'var(--primary); font-weight: bold;' : 'var(--dark);' ?> display: flex; justify-content: space-between;">
                                        <span><?= Helper::e($c['name']) ?></span>
                                        <i class="fa-solid fa-angle-right" style="font-size: 0.75rem; color: var(--dark-muted);"></i>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </aside>

            </div>
        <?php else: ?>
            <div style="background: white; padding: 3rem; text-align: center; border-radius: 8px; border: 1px solid var(--border-color);">
                <i class="fa-solid fa-folder-open" style="font-size: 3rem; color: #94A3B8; margin-bottom: 1rem;"></i>
                <h3>এই বিভাগে এখনো কোনো প্রতিবেদন প্রকাশিত হয়নি।</h3>
                <a href="/" class="btn-filter-submit" style="display: inline-block; margin-top: 1rem; text-decoration: none;">প্রচ্ছদে ফিরে যান</a>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php require_once ROOT_PATH . '/views/frontend/layouts/footer.php'; ?>
