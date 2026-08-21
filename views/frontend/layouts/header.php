<?php
/**
 * GenzNewz — Public Frontend Header Layout
 */
$siteTitle = $pageTitle ?? Helper::getSetting('site_title', APP_TITLE);
$siteName = Helper::getSetting('site_name', APP_NAME);
$siteTagline = Helper::getSetting('site_tagline', APP_TAGLINE);
$activeCategorySlug = $category['slug'] ?? '';
$categoriesNav = Category::getActive();
$todayEditionHeader = Edition::getTodayEdition();
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Helper::e($siteTitle) ?></title>
    
    <!-- Meta SEO -->
    <meta name="description" content="<?= Helper::e(Helper::getSetting('seo_description', 'GenzNewz — আধুনিক ডিজিটাল বাংলা সংবাদপত্র ও প্রিমিয়াম ই-পেপার পোর্টাল')) ?>">
    <meta name="theme-color" content="#0B6B3A">
    
    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Custom Stylesheet (No Bootstrap in public view) -->
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>

    <!-- 1. Top Bar -->
    <div class="top-bar">
        <div class="container top-bar-inner">
            <div class="top-date-info">
                <span><i class="fa-regular fa-calendar"></i> <?= Helper::formatBengaliDate(date('Y-m-d')) ?></span>
                <span><i class="fa-solid fa-location-dot"></i> কলকাতা</span>
                <span><i class="fa-regular fa-clock"></i> <strong id="live-bengali-clock">--:--:--</strong></span>
            </div>
            <div class="top-links">
                <?php if ($todayEditionHeader): ?>
                    <a href="/edition/<?= $todayEditionHeader['slug'] ?>" class="btn-epaper-pill">
                        <i class="fa-solid fa-newspaper"></i> আজকের ই-পেপার
                    </a>
                <?php endif; ?>
                <a href="/archive"><i class="fa-solid fa-box-archive"></i> সংরক্ষণাগার</a>
                <?php if (Auth::check()): ?>
                    <?php if (Auth::isAdmin()): ?>
                        <a href="/admin/dashboard" style="color: #FFD700;"><i class="fa-solid fa-gauge-high"></i> অ্যাডমিন ড্যাশবোর্ড</a>
                    <?php else: ?>
                        <a href="/reporter/dashboard" style="color: #FFD700;"><i class="fa-solid fa-pen-nib"></i> রিপোর্টার পোর্টাল</a>
                    <?php endif; ?>
                    <a href="/logout"><i class="fa-solid fa-right-from-bracket"></i> লগআউট</a>
                <?php else: ?>
                    <a href="/login"><i class="fa-solid fa-user-lock"></i> পোর্টাল লগইন</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- 2. Header Masthead -->
    <header class="masthead-section">
        <div class="container masthead-grid">
            <div class="masthead-left">
                <div><strong>রেজিস্টার্ড ডিজিটাল নিউজ পোর্টাল</strong></div>
                <div>আইএসএসএন / আরএনআই অনুমোদিত প্রকাশনা</div>
            </div>
            <div class="masthead-center">
                <a href="/" class="site-logo">
                    <div class="logo-main-text"><?= Helper::e($siteName) ?></div>
                    <div class="logo-tagline"><?= Helper::e($siteTagline) ?></div>
                </a>
            </div>
            <div class="masthead-right">
                <form action="/search" method="GET" class="header-search-form">
                    <input type="text" name="q" class="header-search-input" placeholder="সংবাদ অনুসন্ধান করুন..." value="<?= Helper::e($_GET['q'] ?? '') ?>" required>
                    <button type="submit" class="header-search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>
        </div>
    </header>

    <!-- 3. Main Navigation Bar -->
    <nav class="main-navigation-bar">
        <div class="container nav-container">
            <ul class="nav-menu-list">
                <li class="nav-item <?= empty($activeCategorySlug) && !isset($edition) && !isset($filters) ? 'active' : '' ?>">
                    <a href="/"><i class="fa-solid fa-house"></i> প্রচ্ছদ</a>
                </li>
                <?php if ($todayEditionHeader): ?>
                    <li class="nav-item epaper-link">
                        <a href="/edition/<?= $todayEditionHeader['slug'] ?>"><i class="fa-solid fa-file-pdf"></i> ই-পেপার</a>
                    </li>
                <?php endif; ?>
                <?php foreach ($categoriesNav as $cat): ?>
                    <li class="nav-item <?= ($activeCategorySlug === $cat['slug']) ? 'active' : '' ?>">
                        <a href="/category/<?= $cat['slug'] ?>"><?= Helper::e($cat['name']) ?></a>
                    </li>
                <?php endforeach; ?>
                <li class="nav-item">
                    <a href="/archive"><i class="fa-solid fa-calendar-days"></i> মহাফেজখানা</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Flash Alert Messages if any -->
    <?php if ($flashSuccess = Session::getFlash('success')): ?>
        <div class="container" style="margin-top: 1rem;">
            <div style="background: #E8F5E9; border-left: 4px solid #0B6B3A; color: #064D2B; padding: 0.85rem 1.25rem; border-radius: 4px; display: flex; justify-content: space-between; align-items: center;" class="alert-banner">
                <div><i class="fa-solid fa-circle-check"></i> <?= Helper::e($flashSuccess) ?></div>
                <button type="button" class="alert-banner-dismiss" style="background:none; border:none; cursor:pointer; font-size:1.1rem; color:#064D2B;">&times;</button>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($flashError = Session::getFlash('error')): ?>
        <div class="container" style="margin-top: 1rem;">
            <div style="background: #FFEBEE; border-left: 4px solid #D32F2F; color: #C62828; padding: 0.85rem 1.25rem; border-radius: 4px; display: flex; justify-content: space-between; align-items: center;" class="alert-banner">
                <div><i class="fa-solid fa-circle-exclamation"></i> <?= Helper::e($flashError) ?></div>
                <button type="button" class="alert-banner-dismiss" style="background:none; border:none; cursor:pointer; font-size:1.1rem; color:#C62828;">&times;</button>
            </div>
        </div>
    <?php endif; ?>
