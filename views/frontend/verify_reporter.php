<?php
/**
 * GenzNewz — Reporter Public Accreditation Verification View
 */
require_once ROOT_PATH . '/views/frontend/layouts/header.php';
$isExpired = strtotime($profile['valid_until']) < time();
$isValid = ($profile['user_status'] === 'active' && $profile['id_card_status'] === 'active' && !$isExpired);
?>

<main class="main-content-layout">
    <div class="container" style="max-width: 680px;">
        
        <div style="background: white; border-radius: 12px; border: 1px solid var(--border-color); overflow: hidden; box-shadow: var(--shadow-lg);">
            
            <!-- Header Status Banner -->
            <div style="background: <?= $isValid ? '#0B6B3A' : '#D32F2F' ?>; color: white; padding: 1.5rem; text-align: center;">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">
                    <i class="fa-solid <?= $isValid ? 'fa-circle-check' : 'fa-circle-xmark' ?>"></i>
                </div>
                <h1 style="font-family: var(--font-heading); font-size: 1.5rem; margin-bottom: 0.25rem;">
                    <?= $isValid ? 'ভেরিফাইড ও অনুমোদিত প্রেস অ্যাক্রেডিটেশন' : 'অ্যাক্রেডিটেশন মেয়াদোত্তীর্ণ বা নিষ্ক্রিয়' ?>
                </h1>
                <div style="font-size: 0.85rem; opacity: 0.9;">
                    GenzNewz ডিজিটাল প্রেস ইনফরমেশন অ্যান্ড অথেনটিকেশন ব্যুরো
                </div>
            </div>

            <!-- Reporter Details Sheet -->
            <div style="padding: 2rem;">
                
                <div style="display: flex; gap: 1.5rem; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
                    <img src="<?= Helper::e($profile['profile_photo'] ?: '/storage/uploads/reporters/default_reporter.jpg') ?>" alt="<?= Helper::e($profile['full_name']) ?>" style="width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary-light);">
                    <div>
                        <h2 style="font-family: var(--font-heading); font-size: 1.45rem; color: var(--dark); margin-bottom: 0.2rem;">
                            <?= Helper::e($profile['full_name']) ?>
                        </h2>
                        <div style="font-size: 0.95rem; font-weight: 600; color: var(--primary);">
                            <?= Helper::e($profile['designation']) ?>
                        </div>
                        <div style="font-size: 0.85rem; color: var(--dark-muted); margin-top: 0.2rem;">
                            অফিসিয়াল প্রেস আইডি: <strong><?= Helper::e($profile['reporter_id']) ?></strong>
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; font-size: 0.9rem; margin-bottom: 1.5rem;">
                    <div style="background: #F8FAFC; padding: 0.75rem; border-radius: 6px;">
                        <span style="color: var(--dark-muted); display: block; font-size: 0.75rem;">এমপ্লয়ি কোড:</span>
                        <strong><?= Helper::e($profile['employee_code'] ?? 'N/A') ?></strong>
                    </div>
                    <div style="background: #F8FAFC; padding: 0.75rem; border-radius: 6px;">
                        <span style="color: var(--dark-muted); display: block; font-size: 0.75rem;">অ্যাসাইনড এলাকা / ব্যুরো:</span>
                        <strong><?= Helper::e($profile['assigned_area']) ?></strong>
                    </div>
                    <div style="background: #F8FAFC; padding: 0.75rem; border-radius: 6px;">
                        <span style="color: var(--dark-muted); display: block; font-size: 0.75rem;">রক্তের গ্রুপ:</span>
                        <strong><?= Helper::e($profile['blood_group'] ?? 'N/A') ?></strong>
                    </div>
                    <div style="background: #F8FAFC; padding: 0.75rem; border-radius: 6px;">
                        <span style="color: var(--dark-muted); display: block; font-size: 0.75rem;">কার্ডের বৈধতা:</span>
                        <strong style="color: <?= $isExpired ? '#D32F2F' : '#0B6B3A' ?>;">
                            <?= Helper::formatBengaliDate($profile['valid_until']) ?>
                        </strong>
                    </div>
                </div>

                <div style="border-top: 1px solid var(--border-color); padding-top: 1.25rem; font-size: 0.82rem; color: var(--dark-muted); line-height: 1.6; text-align: center;">
                    এই পরিচয়পত্রটি GenzNewz সংবাদ সংস্থার প্রশাসনিক কার্যালয় দ্বারা স্বাক্ষরিত ও জারি করা। যেকোনো প্রশাসনিক তদন্তে এই কিউআর ভেরিফিকেশন বৈধ প্রমাণ হিসেবে গণ্য হবে।
                </div>

            </div>

        </div>

    </div>
</main>

<?php require_once ROOT_PATH . '/views/frontend/layouts/footer.php'; ?>
