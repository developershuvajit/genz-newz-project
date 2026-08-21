<?php
/**
 * GenzNewz — Public Frontend Footer Layout
 */
$footerCats = Category::getActive();
?>
    <!-- Main Footer -->
    <footer class="main-footer-section">
        <div class="container">
            <div class="footer-grid-layout">
                <!-- Col 1: About & Brand -->
                <div class="footer-col">
                    <h3 style="font-family: var(--font-heading); color: #FFD700; font-size: 1.8rem; margin-bottom: 0.5rem;"><?= Helper::e(Helper::getSetting('site_name', APP_NAME)) ?></h3>
                    <p style="font-size: 0.9rem; color: #CBD5E1; line-height: 1.6; margin-bottom: 1rem;">
                        <?= Helper::e(Helper::getSetting('site_tagline', 'সত্যের সন্ধানে অবিচল — সার্বক্ষণিক নির্ভরযোগ্য ডিজিটাল সংবাদপত্র ও ই-পেপার সংস্করণ')) ?>
                    </p>
                    <div style="display: flex; gap: 0.75rem;">
                        <a href="<?= Helper::e(Helper::getSetting('social_facebook', '#')) ?>" style="width: 36px; height: 36px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="<?= Helper::e(Helper::getSetting('social_twitter', '#')) ?>" style="width: 36px; height: 36px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="<?= Helper::e(Helper::getSetting('social_youtube', '#')) ?>" style="width: 36px; height: 36px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>

                <!-- Col 2: Categories -->
                <div class="footer-col">
                    <h4>বিভাগসমূহ</h4>
                    <ul class="footer-links-list">
                        <?php foreach (array_slice($footerCats, 0, 5) as $fc): ?>
                            <li><a href="/category/<?= $fc['slug'] ?>"><i class="fa-solid fa-angle-right" style="font-size: 0.75rem;"></i> <?= Helper::e($fc['name']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Col 3: Quick Links -->
                <div class="footer-col">
                    <h4>গুরুত্বপূর্ণ লিংক</h4>
                    <ul class="footer-links-list">
                        <li><a href="/archive"><i class="fa-solid fa-angle-right" style="font-size: 0.75rem;"></i> ই-পেপার আর্কাইভ</a></li>
                        <li><a href="/search"><i class="fa-solid fa-angle-right" style="font-size: 0.75rem;"></i> সংবাদ অনুসন্ধান</a></li>
                        <li><a href="/login"><i class="fa-solid fa-angle-right" style="font-size: 0.75rem;"></i> রিপোর্টার লগইন</a></li>
                        <li><a href="/admin/login"><i class="fa-solid fa-angle-right" style="font-size: 0.75rem;"></i> সম্পাদকীয় দপ্তর</a></li>
                    </ul>
                </div>

                <!-- Col 4: Contact & Office -->
                <div class="footer-col">
                    <h4>প্রধান কার্যালয়</h4>
                    <p style="font-size: 0.88rem; color: #CBD5E1; line-height: 1.6; margin-bottom: 0.5rem;">
                        <i class="fa-solid fa-location-dot" style="color: #FFD700;"></i> <?= Helper::e(Helper::getSetting('contact_address', '১২/এ, আনন্দবাজার লেন, বি.বি.ডি বাগ, কলকাতা — ৭০০০০১')) ?>
                    </p>
                    <p style="font-size: 0.88rem; color: #CBD5E1; margin-bottom: 0.5rem;">
                        <i class="fa-solid fa-phone" style="color: #FFD700;"></i> <?= Helper::e(Helper::getSetting('contact_phone', '+91 33 2248 0000')) ?>
                    </p>
                    <p style="font-size: 0.88rem; color: #CBD5E1;">
                        <i class="fa-solid fa-envelope" style="color: #FFD700;"></i> <?= Helper::e(Helper::getSetting('contact_email', 'editor@genznewz.com')) ?>
                    </p>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="footer-bottom-bar">
                <div>
                    <?= Helper::e(Helper::getSetting('footer_text', '© ' . date('Y') . ' GenzNewz Digital Media Pvt Ltd. সর্বস্বত্ব সংরক্ষিত।')) ?>
                </div>
                <div>
                    কোর পিএইচপি ও সিকিউর এসকিউএল ইঞ্জিনের মাধ্যমে নির্মিত
                </div>
            </div>
        </div>
    </footer>

    <!-- Public JS -->
    <script src="/public/js/main.js"></script>
    <?php if (isset($extraScripts)) echo $extraScripts; ?>
</body>
</html>
