<?php
/**
 * GenzNewz — Public 404 Error View
 */
require_once ROOT_PATH . '/views/frontend/layouts/header.php';
?>

<main class="main-content-layout" style="min-height: calc(100vh - 350px); display: flex; align-items: center;">
    <div class="container" style="max-width: 600px; text-align: center;">
        
        <div style="background: white; border-radius: 12px; border: 1px solid var(--border-color); padding: 3rem 2rem; box-shadow: var(--shadow-md);">
            <div style="font-size: 5rem; font-weight: 900; color: #0B6B3A; line-height: 1; margin-bottom: 1rem; font-family: var(--font-heading);">
                ৪০৪
            </div>
            <h1 style="font-family: var(--font-heading); font-size: 1.6rem; margin-bottom: 0.75rem; color: var(--dark);">
                অনুরোধ করা পাতাটি খুঁজে পাওয়া যায়নি
            </h1>
            <p style="color: var(--dark-muted); font-size: 0.95rem; margin-bottom: 1.5rem; line-height: 1.6;">
                <?= Helper::e($message ?? 'আপনি যে সংবাদ বা সংস্করণটি খুঁজছেন সেটি স্থানান্তরিত বা মুছে ফেলা হয়েছে।') ?>
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center;">
                <a href="/" class="btn-filter-submit" style="text-decoration: none; padding: 0.6rem 1.25rem;">
                    <i class="fa-solid fa-house"></i> প্রচ্ছদে ফিরে যান
                </a>
                <a href="/archive" class="btn-filter-submit" style="background: #E2E8F0; color: #1E293B; text-decoration: none; padding: 0.6rem 1.25rem;">
                    <i class="fa-solid fa-box-archive"></i> ই-পেপার মহাফেজখানা
                </a>
            </div>
        </div>

    </div>
</main>

<?php require_once ROOT_PATH . '/views/frontend/layouts/footer.php'; ?>
