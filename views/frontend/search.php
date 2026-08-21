<?php
/**
 * GenzNewz — Public Search Results View
 */
require_once ROOT_PATH . '/views/frontend/layouts/header.php';
?>

<main class="main-content-layout">
    <div class="container">
        
        <div class="section-heading-bar">
            <h1 class="section-heading-title">
                <i class="fa-solid fa-magnifying-glass" style="color: var(--primary);"></i> সংবাদ ও প্রতিবেদন অনুসন্ধান
            </h1>
        </div>

        <div style="background: white; border: 1px solid var(--border-color); border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem;">
            <form action="/search" method="GET" style="display: flex; gap: 0.75rem;">
                <input type="text" name="q" value="<?= Helper::e($query) ?>" class="form-input-control" placeholder="শব্দ বা বিষয় দিয়ে খুঁজুন (যেমন: বাজেট, ডার্বি, মেট্রো)..." style="font-size: 1rem; padding: 0.75rem 1rem;" required>
                <button type="submit" class="btn-filter-submit" style="padding: 0 1.5rem; font-size: 1rem;"><i class="fa-solid fa-magnifying-glass"></i> খুঁজুন</button>
            </form>
        </div>

        <?php if (!empty($query)): ?>
            <div style="margin-bottom: 1.5rem; font-size: 1rem; color: var(--dark-muted);">
                '<strong><?= Helper::e($query) ?></strong>' এর জন্য <strong><?= Helper::formatBengaliNumber($results['total']) ?></strong>টি ফলাফল পাওয়া গেছে।
            </div>

            <?php if (!empty($results['data'])): ?>
                <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                    <?php foreach ($results['data'] as $resArt): ?>
                        <article style="background: white; border-radius: 8px; border: 1px solid var(--border-color); padding: 1.25rem; box-shadow: var(--shadow-sm); display: flex; gap: 1.25rem;">
                            <?php if (!empty($resArt['featured_image'])): ?>
                                <div style="width: 180px; height: 120px; flex-shrink: 0; border-radius: 6px; overflow: hidden; background: #F1F5F9;">
                                    <a href="/article/<?= $resArt['slug'] ?>">
                                        <img src="<?= Helper::e($resArt['featured_image']) ?>" alt="<?= Helper::e($resArt['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div>
                                <div style="font-size: 0.8rem; color: var(--dark-muted); margin-bottom: 0.35rem;">
                                    <span class="category-tag"><?= Helper::e($resArt['category_name']) ?></span> &bull; 
                                    <span><?= Helper::formatBengaliDate($resArt['published_at'] ?? $resArt['created_at']) ?></span>
                                </div>
                                <h3 style="font-family: var(--font-heading); font-size: 1.2rem; font-weight: 700; margin-bottom: 0.5rem;">
                                    <a href="/article/<?= $resArt['slug'] ?>"><?= Helper::e($resArt['title']) ?></a>
                                </h3>
                                <p style="font-size: 0.9rem; color: #475569; line-height: 1.5;">
                                    <?= Helper::truncate(Helper::e($resArt['short_description']), 150) ?>
                                </p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($results['total_pages'] > 1): ?>
                    <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 2rem;">
                        <?php for ($p = 1; $p <= $results['total_pages']; $p++): ?>
                            <a href="?q=<?= urlencode($query) ?>&page=<?= $p ?>" 
                               style="padding: 0.5rem 1rem; border-radius: 4px; border: 1px solid var(--border-color); background: <?= ($p == $results['current_page']) ? 'var(--primary)' : 'white' ?>; color: <?= ($p == $results['current_page']) ? 'white' : 'var(--dark)' ?>; font-weight: bold;">
                                <?= Helper::formatBengaliNumber($p) ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div style="background: white; padding: 3rem; text-align: center; border-radius: 8px; border: 1px solid var(--border-color);">
                    <i class="fa-solid fa-magnifying-glass" style="font-size: 3rem; color: #94A3B8; margin-bottom: 1rem;"></i>
                    <h3>কোনো ফলাফল খুঁজে পাওয়া যায়নি</h3>
                    <p style="color: var(--dark-muted); margin-top: 0.5rem;">বানান সঠিক কিনা পরীক্ষা করুন অথবা ভিন্ন কোনো শব্দ দিয়ে চেষ্টা করুন।</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</main>

<?php require_once ROOT_PATH . '/views/frontend/layouts/footer.php'; ?>
