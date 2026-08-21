<?php
/**
 * GenzNewz — Admin Sidebar Component
 */
$currentUri = $_SERVER['REQUEST_URI'] ?? '';
?>
<aside class="admin-sidebar" id="admin-sidebar">
    <div class="sidebar-brand">
        <i class="fa-solid fa-newspaper text-warning fs-3"></i>
        <div>
            <div class="sidebar-brand-title">GENZNEWZ</div>
            <div class="small text-white-50" style="font-size: 0.72rem;">সুপার অ্যাডমিন প্যানেল</div>
        </div>
    </div>

    <ul class="sidebar-menu">
        <li class="sidebar-item <?= ($currentUri === '/admin/dashboard') ? 'active' : '' ?>">
            <a href="/admin/dashboard"><i class="fa-solid fa-gauge-high"></i> ড্যাশবোর্ড</a>
        </li>

        <div class="sidebar-header-label">ই-পেপার প্রকাশনা</div>
        
        <li class="sidebar-item <?= str_starts_with($currentUri, '/admin/editions') && !str_contains($currentUri, 'pages') ? 'active' : '' ?>">
            <a href="/admin/editions"><i class="fa-solid fa-book-open"></i> সংস্করণ ব্যবস্থাপনা</a>
        </li>
        <li class="sidebar-item <?= str_contains($currentUri, '/admin/pages') || str_contains($currentUri, '/pages') ? 'active' : '' ?>">
            <a href="/admin/pages"><i class="fa-solid fa-file-image"></i> পাতা ও বাল্ক আপলোড</a>
        </li>
        <li class="sidebar-item <?= ($currentUri === '/admin/edition-types') ? 'active' : '' ?>">
            <a href="/admin/edition-types"><i class="fa-solid fa-tags"></i> সংস্করণ ধরণ</a>
        </li>

        <div class="sidebar-header-label">সংবাদ ও সম্পাদকীয়</div>

        <li class="sidebar-item <?= ($currentUri === '/admin/articles') ? 'active' : '' ?>">
            <a href="/admin/articles"><i class="fa-solid fa-newspaper"></i> সকল সংবাদ</a>
        </li>
        <li class="sidebar-item <?= ($currentUri === '/admin/articles/pending') ? 'active' : '' ?>">
            <a href="/admin/articles/pending"><i class="fa-solid fa-hourglass-half text-warning"></i> পর্যালোচনায় অপেক্ষমাণ</a>
        </li>
        <li class="sidebar-item <?= ($currentUri === '/admin/articles/create') ? 'active' : '' ?>">
            <a href="/admin/articles/create"><i class="fa-solid fa-pen-to-square"></i> নতুন সংবাদ লিখুন</a>
        </li>
        <li class="sidebar-item <?= ($currentUri === '/admin/categories') ? 'active' : '' ?>">
            <a href="/admin/categories"><i class="fa-solid fa-layer-group"></i> বিভাগ ও ক্যাটাগরি</a>
        </li>

        <div class="sidebar-header-label">প্রেস ও রিপোর্টার ম্যানেজমেন্ট</div>

        <li class="sidebar-item <?= str_starts_with($currentUri, '/admin/reporters') ? 'active' : '' ?>">
            <a href="/admin/reporters"><i class="fa-solid fa-id-card"></i> রিপোর্টার ও প্রেস আইডি</a>
        </li>
        <li class="sidebar-item <?= ($currentUri === '/admin/reporters/create') ? 'active' : '' ?>">
            <a href="/admin/reporters/create"><i class="fa-solid fa-user-plus"></i> নতুন সাংবাদিক নিবন্ধন</a>
        </li>

        <div class="sidebar-header-label">সিস্টেম ও কনফিগারেশন</div>

        <li class="sidebar-item <?= ($currentUri === '/admin/media') ? 'active' : '' ?>">
            <a href="/admin/media"><i class="fa-solid fa-photo-film"></i> মিডিয়া লাইব্রেরি</a>
        </li>
        <li class="sidebar-item <?= ($currentUri === '/admin/settings') ? 'active' : '' ?>">
            <a href="/admin/settings"><i class="fa-solid fa-gear"></i> পোর্টাল সেটিংস</a>
        </li>
        <li class="sidebar-item <?= ($currentUri === '/admin/backup') ? 'active' : '' ?>">
            <a href="/admin/backup"><i class="fa-solid fa-database"></i> ডেটাবেস ব্যাকআপ</a>
        </li>
        <li class="sidebar-item <?= ($currentUri === '/admin/activity-logs') ? 'active' : '' ?>">
            <a href="/admin/activity-logs"><i class="fa-solid fa-shield-halved"></i> অডিট ও সিকিউরিটি লগ</a>
        </li>

        <li class="sidebar-item mt-3">
            <a href="/admin/logout" class="text-danger"><i class="fa-solid fa-arrow-right-from-bracket"></i> লগআউট</a>
        </li>
    </ul>
</aside>
