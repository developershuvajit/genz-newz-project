<?php
/**
 * GenzNewz — Public Archive View
 */
require_once ROOT_PATH . '/views/frontend/layouts/header.php';
?>

<main class="main-content-layout">
    <div class="container">
        
        <div class="section-heading-bar">
            <h1 class="section-heading-title">
                <i class="fa-solid fa-box-archive" style="color: var(--primary);"></i> ই-পেপার সংরক্ষণাগার (মহাফেজখানা)
            </h1>
        </div>

        <!-- Filter Form -->
        <div class="archive-filter-box">
            <form action="/archive" method="GET" class="filter-form-grid">
                <div class="form-group-field">
                    <label>তারিখ অনুযায়ী খুঁজুন</label>
                    <input type="date" name="date" class="form-input-control" value="<?= Helper::e($filters['date'] ?? '') ?>">
                </div>

                <div class="form-group-field">
                    <label>মাস</label>
                    <select name="month" class="form-input-control">
                        <option value="">সকল মাস</option>
                        <?php
                        $months = [1=>'জানুয়ারি', 2=>'ফেব্রুয়ারি', 3=>'মার্চ', 4=>'এপ্রিল', 5=>'মে', 6=>'জুন', 7=>'জুলাই', 8=>'আগস্ট', 9=>'সেপ্টেম্বর', 10=>'অক্টোবর', 11=>'নভেম্বর', 12=>'ডিসেম্বর'];
                        foreach ($months as $mNum => $mName):
                        ?>
                            <option value="<?= $mNum ?>" <?= (isset($filters['month']) && (int)$filters['month'] === $mNum) ? 'selected' : '' ?>><?= $mName ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group-field">
                    <label>বছর</label>
                    <select name="year" class="form-input-control">
                        <option value="">সকল বছর</option>
                        <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                            <option value="<?= $y ?>" <?= (isset($filters['year']) && (int)$filters['year'] === $y) ? 'selected' : '' ?>><?= Helper::formatBengaliNumber($y) ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group-field">
                    <label>সংস্করণ ধরণ</label>
                    <select name="edition_type" class="form-input-control">
                        <option value="">সকল সংস্করণ</option>
                        <?php foreach ($editionTypes as $et): ?>
                            <option value="<?= $et['id'] ?>" <?= (isset($filters['type_id']) && (int)$filters['type_id'] === (int)$et['id']) ? 'selected' : '' ?>><?= Helper::e($et['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <button type="submit" class="btn-filter-submit"><i class="fa-solid fa-filter"></i> ফিল্টার করুন</button>
                </div>
            </form>
        </div>

        <!-- Archive Grid -->
        <?php if (!empty($editions['data'])): ?>
            <div class="archive-grid">
                <?php foreach ($editions['data'] as $ed): ?>
                    <div class="archive-card">
                        <div class="archive-card-thumb">
                            <a href="/edition/<?= $ed['slug'] ?>">
                                <img src="<?= Helper::e($ed['cover_image'] ?: '/storage/pages/thumb/page_1.svg') ?>" alt="<?= Helper::e($ed['title']) ?>">
                            </a>
                            <span style="position: absolute; top: 10px; right: 10px; background: rgba(11, 107, 58, 0.9); color: white; font-size: 0.75rem; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: bold;">
                                <?= Helper::e($ed['edition_type_name'] ?? 'ডিজিটাল') ?>
                            </span>
                        </div>
                        <div class="archive-card-body">
                            <div style="font-size: 0.8rem; color: var(--dark-muted); margin-bottom: 0.35rem;">
                                <i class="fa-regular fa-calendar-check"></i> <?= Helper::formatBengaliDate($ed['edition_date']) ?>
                            </div>
                            <h3 class="archive-card-title">
                                <a href="/edition/<?= $ed['slug'] ?>"><?= Helper::e($ed['title']) ?></a>
                            </h3>
                            <div style="font-size: 0.85rem; color: var(--dark-muted); margin-bottom: 1rem; flex-grow: 1;">
                                <i class="fa-solid fa-layer-group"></i> মোট পাতা: <?= Helper::formatBengaliNumber($ed['page_count'] ?: 8) ?>
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="/edition/<?= $ed['slug'] ?>" class="btn-read-epaper" style="flex: 1; padding: 0.45rem 0.75rem; font-size: 0.85rem;">
                                    <i class="fa-solid fa-book-open"></i> পড়ুন
                                </a>
                                <a href="/download/edition/<?= $ed['slug'] ?>" class="btn-read-epaper" style="background: #E2E8F0; color: #1E293B; padding: 0.45rem 0.75rem; font-size: 0.85rem;">
                                    <i class="fa-solid fa-download"></i> PDF
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($editions['total_pages'] > 1): ?>
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 2rem;">
                    <?php for ($p = 1; $p <= $editions['total_pages']; $p++): ?>
                        <a href="?page=<?= $p ?><?= !empty($filters['date']) ? '&date=' . $filters['date'] : '' ?>" 
                           style="padding: 0.5rem 1rem; border-radius: 4px; border: 1px solid var(--border-color); background: <?= ($p == $editions['current_page']) ? 'var(--primary)' : 'white' ?>; color: <?= ($p == $editions['current_page']) ? 'white' : 'var(--dark)' ?>; font-weight: bold;">
                            <?= Helper::formatBengaliNumber($p) ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div style="background: white; padding: 3rem; text-align: center; border-radius: 8px; border: 1px solid var(--border-color);">
                <i class="fa-solid fa-newspaper" style="font-size: 3rem; color: #94A3B8; margin-bottom: 1rem;"></i>
                <h3>এই অনুসন্ধানে কোনো ই-পেপার সংস্করণ পাওয়া যায়নি।</h3>
                <p style="color: var(--dark-muted); margin-top: 0.5rem;">অনুগ্রহ করে তারিখ বা ফিল্টার পরিবর্তন করে পুনরায় অনুসন্ধান করুন।</p>
                <a href="/archive" class="btn-filter-submit" style="display: inline-block; margin-top: 1rem; text-decoration: none;">সকল সংস্করণ দেখুন</a>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php require_once ROOT_PATH . '/views/frontend/layouts/footer.php'; ?>
