<?php
/**
 * GenzNewz — Unified Login Portal View
 */
require_once ROOT_PATH . '/views/frontend/layouts/header.php';
?>

<main class="main-content-layout" style="min-height: calc(100vh - 350px); display: flex; align-items: center;">
    <div class="container" style="max-width: 480px;">
        
        <div style="background: white; border-radius: 12px; border: 1px solid var(--border-color); padding: 2.5rem; box-shadow: var(--shadow-lg);">
            
            <div style="text-align: center; margin-bottom: 2rem;">
                <div style="width: 60px; height: 60px; background: var(--primary-light); color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; margin: 0 auto 1rem;">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <h1 style="font-family: var(--font-heading); font-size: 1.75rem; font-weight: 800; color: var(--dark); margin-bottom: 0.25rem;">
                    পোর্টাল লগইন
                </h1>
                <p style="font-size: 0.85rem; color: var(--dark-muted);">
                    অ্যাডমিন ও সাংবাদিক ড্যাশবোর্ডে প্রবেশ করতে লগইন করুন
                </p>
            </div>

            <!-- Demo Credentials Quick Selector -->
            <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 6px; padding: 0.85rem; margin-bottom: 1.5rem; font-size: 0.82rem;">
                <div style="font-weight: 700; color: var(--primary-dark); margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.35rem;">
                    <i class="fa-solid fa-bolt"></i> এক-ক্লিকে ডেমো লগইন ক্রেডেনশিয়াল পূরণ করুন:
                </div>
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    <button type="button" onclick="fillCreds('admin@genznewz.com', 'admin123')" style="background: var(--primary); color: white; border: none; padding: 0.3rem 0.6rem; border-radius: 4px; cursor: pointer; font-size: 0.78rem;">
                        <i class="fa-solid fa-user-shield"></i> অ্যাডমিন (admin@genznewz.com)
                    </button>
                    <button type="button" onclick="fillCreds('rahul@genznewz.com', 'reporter123')" style="background: #0284C7; color: white; border: none; padding: 0.3rem 0.6rem; border-radius: 4px; cursor: pointer; font-size: 0.78rem;">
                        <i class="fa-solid fa-pen-nib"></i> রিপোর্টার (GNZ-RPT-0001)
                    </button>
                </div>
            </div>

            <form action="/login" method="POST">
                <?= CSRF::field() ?>

                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--dark); margin-bottom: 0.35rem;">
                        ইমেইল বা রিপোর্টার আইডি
                    </label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: var(--dark-muted); font-size: 0.9rem;">
                            <i class="fa-regular fa-envelope"></i>
                        </span>
                        <input type="text" name="identity" id="input-identity" class="form-input-control" placeholder="যেমন: admin@genznewz.com বা GNZ-RPT-0001" style="padding-left: 2.5rem;" required>
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--dark); margin-bottom: 0.35rem;">
                        গোপন পাসওয়ার্ড
                    </label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: var(--dark-muted); font-size: 0.9rem;">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <input type="password" name="password" id="input-password" class="form-input-control" placeholder="••••••••" style="padding-left: 2.5rem;" required>
                    </div>
                </div>

                <button type="submit" class="btn-filter-submit" style="width: 100%; padding: 0.75rem; font-size: 1rem; border-radius: 6px; box-shadow: var(--shadow-sm);">
                    <i class="fa-solid fa-right-to-bracket"></i> সুরক্ষিত লগইন করুন
                </button>
            </form>

            <div style="text-align: center; margin-top: 1.5rem; font-size: 0.85rem; color: var(--dark-muted);">
                <a href="/" style="color: var(--primary); text-decoration: underline;">
                    <i class="fa-solid fa-arrow-left"></i> সংবাদ পোর্টালের প্রচ্ছদে ফিরে যান
                </a>
            </div>

        </div>

    </div>
</main>

<script>
    function fillCreds(user, pass) {
        document.getElementById('input-identity').value = user;
        document.getElementById('input-password').value = pass;
    }
</script>

<?php require_once ROOT_PATH . '/views/frontend/layouts/footer.php'; ?>
