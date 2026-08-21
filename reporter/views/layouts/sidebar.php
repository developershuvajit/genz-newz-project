<?php
/**
 * GenzNewz — Reporter Sidebar Layout
 */
$currentUri = $_SERVER['REQUEST_URI'] ?? '';
$reporterProfile = Auth::reporterProfile();
?>
<aside class="reporter-sidebar" id="reporter-sidebar">
    <div class="sidebar-brand">
        <i class="fa-solid fa-feather-pointed text-warning fs-3"></i>
        <div>
            <div class="sidebar-brand-title">GENZNEWZ</div>
            <div class="small text-white-50" style="font-size: 0.72rem;">সাংবাদিক ডেস্ক</div>
        </div>
    </div>

    <!-- Reporter Profile Snapshot Card -->
    <div class="p-3 m-3 bg-black bg-opacity-25 rounded text-center border border-white border-opacity-10">
        <img src="<?= Helper::e($reporterProfile['profile_photo'] ?? '/storage/uploads/reporters/default_reporter.jpg') ?>" class="rounded-circle border border-2 border-warning mb-2" style="width: 55px; height: 55px; object-fit: cover;" alt="Avatar">
        <div class="fw-bold text-white small text-truncate"><?= Helper::e(Auth::user()['name']) ?></div>
        <div class="small text-warning" style="font-size: 0.75rem;"><?= Helper::e($reporterProfile['reporter_id'] ?? 'REPORTER') ?></div>
    </div>

    <ul class="sidebar-menu">
        <li class="sidebar-item <?= ($currentUri === '/reporter/dashboard') ? 'active' : '' ?>">
            <a href="/reporter/dashboard"><i class="fa-solid fa-gauge-high"></i> ড্যাশবোর্ড</a>
        </li>

        <li class="sidebar-item <?= ($currentUri === '/reporter/articles/create') ? 'active' : '' ?>">
            <a href="/reporter/articles/create"><i class="fa-solid fa-pen-to-square text-warning"></i> সংবাদ লিখুন ও পাঠান</a>
        </li>

        <li class="sidebar-item <?= str_starts_with($currentUri, '/reporter/articles') && !str_contains($currentUri, 'create') ? 'active' : '' ?>">
            <a href="/reporter/articles"><i class="fa-solid fa-newspaper"></i> আমার সংবাদসমূহ</a>
        </li>

        <li class="sidebar-item <?= ($currentUri === '/reporter/id-card') ? 'active' : '' ?>">
            <a href="/reporter/id-card"><i class="fa-solid fa-id-card text-info"></i> প্রেস আইডি কার্ড</a>
        </li>

        <li class="sidebar-item <?= ($currentUri === '/reporter/notifications') ? 'active' : '' ?>">
            <a href="/reporter/notifications"><i class="fa-solid fa-bell"></i> নোটিফিকেশন</a>
        </li>

        <li class="sidebar-item <?= ($currentUri === '/reporter/profile') ? 'active' : '' ?>">
            <a href="/reporter/profile"><i class="fa-solid fa-user-gear"></i> প্রোফাইল সেটিংস</a>
        </li>

        <li class="sidebar-item mt-4">
            <a href="/reporter/logout" class="text-danger"><i class="fa-solid fa-arrow-right-from-bracket"></i> লগআউট</a>
        </li>
    </ul>
</aside>
