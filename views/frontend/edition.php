<?php
/**
 * GenzNewz — Public ePaper Interactive Viewer View
 */
require_once ROOT_PATH . '/views/frontend/layouts/header.php';
?>

<div class="epaper-viewer-container" id="epaper-stage-view">
    
    <!-- 1. Viewer Control Toolbar -->
    <div class="epaper-toolbar">
        <div class="toolbar-left">
            <a href="/" class="toolbar-btn"><i class="fa-solid fa-arrow-left"></i> প্রচ্ছদ</a>
            <div class="edition-title-label">
                <i class="fa-regular fa-newspaper"></i> <?= Helper::e($edition['title']) ?> 
                <span style="font-size: 0.85rem; color: #9CA3AF; font-weight: normal;">(<?= Helper::formatBengaliDate($edition['edition_date']) ?>)</span>
            </div>
        </div>

        <div class="toolbar-center">
            <!-- Prev Page Button -->
            <button type="button" class="toolbar-btn btn-page-prev" title="পূর্ববর্তী পাতা (Shortcut: Left Arrow)">
                <i class="fa-solid fa-chevron-left"></i> পূর্ববর্তী পাতা
            </button>

            <!-- Page Selection Dropdown -->
            <select class="page-select-dropdown" id="page-select-dropdown">
                <?php foreach ($allPages as $p): ?>
                    <option value="<?= $p['page_number'] ?>" <?= ($p['page_number'] == $pageNumber) ? 'selected' : '' ?>>
                        পাতা <?= Helper::formatBengaliNumber($p['page_number']) ?> / <?= Helper::formatBengaliNumber($totalPages) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Next Page Button -->
            <button type="button" class="toolbar-btn btn-page-next" title="পরবর্তী পাতা (Shortcut: Right Arrow)">
                পরবর্তী পাতা <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>

        <div class="toolbar-right">
            <!-- Zoom Controls -->
            <button type="button" class="toolbar-btn" id="btn-zoom-out" title="জুম কমান (-)"><i class="fa-solid fa-magnifying-glass-minus"></i></button>
            <span id="zoom-value-badge" style="font-size: 0.8rem; color: #FFD700; font-weight: bold; min-width: 45px; text-align: center;">100%</span>
            <button type="button" class="toolbar-btn" id="btn-zoom-in" title="জুম বাড়ান (+)"><i class="fa-solid fa-magnifying-glass-plus"></i></button>
            <button type="button" class="toolbar-btn" id="btn-zoom-reset" title="রিসেট">১০০%</button>
            <button type="button" class="toolbar-btn" id="btn-fit-width" title="স্ক্রিনের মাপে ফিট"><i class="fa-solid fa-arrows-left-right"></i> ফিট</button>
            
            <!-- Fullscreen & Download -->
            <button type="button" class="toolbar-btn" id="btn-fullscreen" title="ফুলস্ক্রিন (F)"><i class="fa-solid fa-expand"></i></button>
            <a href="/download/edition/<?= $edition['slug'] ?>" class="toolbar-btn" style="background: #0B6B3A; border-color: #0E8548; color: white;" title="সম্পূর্ণ PDF ডাউনলোড">
                <i class="fa-solid fa-download"></i> PDF
            </a>
            <button type="button" class="toolbar-btn" onclick="window.print()" title="প্রিন্ট"><i class="fa-solid fa-print"></i></button>
        </div>
    </div>

    <!-- 2. Main Stage (Thumbnails Sidebar + Page Canvas) -->
    <div class="epaper-stage-layout">
        
        <!-- Left Thumbnail Strip -->
        <aside class="epaper-thumb-sidebar">
            <div style="font-size: 0.75rem; color: #9CA3AF; text-align: center; text-transform: uppercase; font-weight: 700; margin-bottom: 0.5rem;">
                পাতাসমূহ
            </div>
            <?php foreach ($allPages as $thumbPage): ?>
                <div class="thumb-item <?= ($thumbPage['page_number'] == $pageNumber) ? 'active' : '' ?>" data-page="<?= $thumbPage['page_number'] ?>">
                    <img src="<?= Helper::e($thumbPage['thumbnail'] ?: $thumbPage['page_image']) ?>" alt="পাতা <?= $thumbPage['page_number'] ?>">
                    <div class="thumb-item-label">পৃষ্ঠা <?= Helper::formatBengaliNumber($thumbPage['page_number']) ?></div>
                </div>
            <?php endforeach; ?>
        </aside>

        <!-- Canvas Stage -->
        <div class="epaper-canvas-wrapper" id="epaper-canvas-wrapper">
            
            <!-- Floating Navigation Arrows -->
            <button type="button" class="stage-arrow-btn stage-arrow-prev btn-page-prev" title="পূর্ববর্তী পাতা">
                <i class="fa-solid fa-angle-left"></i>
            </button>

            <!-- Active Newspaper Sheet -->
            <div class="newspaper-page-sheet" id="newspaper-sheet">
                <img id="epaper-current-image" src="<?= Helper::e($currentPage['page_image']) ?>" alt="<?= Helper::e($currentPage['page_title'] ?? 'ই-পেপার পাতা') ?>">
            </div>

            <button type="button" class="stage-arrow-btn stage-arrow-next btn-page-next" title="পরবর্তী পাতা">
                <i class="fa-solid fa-angle-right"></i>
            </button>

            <!-- Loading Spinner Overlay -->
            <div id="epaper-loading-spinner" style="display: none; position: absolute; inset: 0; background: rgba(17, 24, 39, 0.7); z-index: 40; align-items: center; justify-content: center; color: #FFD700; font-size: 1.5rem;">
                <i class="fa-solid fa-spinner fa-spin"></i> &nbsp; পাতা লোড হচ্ছে...
            </div>
        </div>

    </div>

    <!-- Viewer Bottom Quick Nav / Keyboard Hints -->
    <div style="background: #1F2937; color: #9CA3AF; font-size: 0.8rem; padding: 0.4rem 1.25rem; display: flex; justify-content: space-between; border-top: 1px solid #374151;">
        <div>
            <strong id="epaper-page-title-label" style="color: #E5E7EB;"><?= Helper::e($currentPage['page_title'] ?? "পৃষ্ঠা {$pageNumber}") ?></strong>
        </div>
        <div style="display: flex; gap: 1rem;">
            <span><i class="fa-solid fa-keyboard"></i> কিবোর্ড শর্টকাট:</span>
            <span><strong>← / →</strong> পাতা পরিবর্তন</span>
            <span><strong>+ / -</strong> জুম</span>
            <span><strong>F</strong> ফুলস্ক্রিন</span>
            <span><strong>ডাবল ক্লিক</strong> জুম টগল</span>
        </div>
    </div>

</div>

<!-- Initialize ePaper Viewer JS Engine -->
<script src="/public/js/epaper-viewer.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        window.epaperInstance = new EPaperViewer({
            editionSlug: '<?= $edition['slug'] ?>',
            currentPage: <?= $pageNumber ?>,
            totalPages: <?= $totalPages ?>
        });
    });
</script>

<?php require_once ROOT_PATH . '/views/frontend/layouts/footer.php'; ?>
