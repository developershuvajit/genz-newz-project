/**
 * GENZNEWZ — Public Portal Scripts
 * Vanilla JS • Responsive Utilities • Live Clock
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Live Clock Updates
    const clockEl = document.getElementById('live-bengali-clock');
    if (clockEl) {
        function updateClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('bn-BD', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
            clockEl.textContent = timeStr;
        }
        updateClock();
        setInterval(updateClock, 1000);
    }

    // 2. Mobile Navigation Drawer Toggle
    const mobileMenuBtn = document.getElementById('btn-mobile-menu-toggle');
    const navMenuList = document.querySelector('.nav-menu-list');

    if (mobileMenuBtn && navMenuList) {
        mobileMenuBtn.addEventListener('click', () => {
            navMenuList.classList.toggle('is-open');
        });
    }

    // 3. Auto-dismiss Flash Alert Banners
    document.querySelectorAll('.alert-banner-dismiss').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const banner = e.target.closest('.alert-banner');
            if (banner) {
                banner.style.opacity = '0';
                setTimeout(() => banner.remove(), 300);
            }
        });
    });

    // 4. Social Sharing Handlers
    document.querySelectorAll('.btn-share-trigger').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const platform = btn.getAttribute('data-platform');
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);

            let shareUrl = '';
            if (platform === 'facebook') {
                shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
            } else if (platform === 'twitter') {
                shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
            } else if (platform === 'whatsapp') {
                shareUrl = `https://api.whatsapp.com/send?text=${title}%20${url}`;
            }

            if (shareUrl) {
                window.open(shareUrl, '_blank', 'width=600,height=450');
            }
        });
    });
});
