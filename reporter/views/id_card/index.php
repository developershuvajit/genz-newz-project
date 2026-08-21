<?php
/**
 * GenzNewz — Reporter Portal Self ID Card View
 */
require_once ROOT_PATH . '/reporter/views/layouts/header.php';
$isExpired = strtotime($profile['valid_until']) < time();
?>

<style>
    .id-card-wrapper {
        display: flex;
        gap: 2.5rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 1.5rem;
    }

    .id-card {
        width: 330px;
        height: 510px;
        border-radius: 14px;
        background: #FFFFFF;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        position: relative;
        overflow: hidden;
        border: 1px solid #CBD5E1;
        display: flex;
        flex-direction: column;
        user-select: none;
    }

    .id-card-front .card-header-band {
        background: linear-gradient(135deg, #064D2B, #0B6B3A);
        color: #FFFFFF;
        padding: 1.1rem 1rem 0.8rem;
        text-align: center;
        position: relative;
        border-bottom: 3px solid #FFD700;
    }

    .id-card-front .card-header-band h4 {
        font-family: 'Noto Serif Bengali', serif;
        font-size: 1.35rem;
        margin: 0;
        color: #FFD700;
        font-weight: 800;
    }

    .id-card-front .card-header-band .card-type-tag {
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #E2E8F0;
        font-weight: 700;
        margin-top: 2px;
    }

    .id-card-front .photo-container {
        text-align: center;
        margin-top: 0.75rem;
    }

    .id-card-front .reporter-photo {
        width: 105px;
        height: 120px;
        border-radius: 8px;
        object-fit: cover;
        border: 3px solid #0B6B3A;
        box-shadow: 0 4px 8px rgba(0,0,0,0.12);
    }

    .id-card-front .card-body-details {
        padding: 0.75rem 1.25rem 0.5rem;
        text-align: center;
        flex-grow: 1;
    }

    .id-card-front .rep-name {
        font-family: 'Noto Serif Bengali', serif;
        font-size: 1.2rem;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 0.15rem;
    }

    .id-card-front .rep-designation {
        font-size: 0.85rem;
        font-weight: 700;
        color: #0B6B3A;
        margin-bottom: 0.6rem;
    }

    .id-card-front .info-table {
        width: 100%;
        font-size: 0.76rem;
        text-align: left;
        border-collapse: collapse;
        margin-top: 0.35rem;
    }

    .id-card-front .info-table td {
        padding: 2.5px 0;
    }

    .id-card-front .info-table td.label-td {
        color: #64748B;
        font-weight: 600;
        width: 42%;
    }

    .id-card-front .info-table td.val-td {
        color: #0F172A;
        font-weight: 700;
    }

    .id-card-front .card-footer-band {
        background: #F8FAFC;
        border-top: 1px solid #E2E8F0;
        padding: 0.5rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .id-card-front .signature-box {
        text-align: center;
    }

    .id-card-front .signature-box img {
        height: 25px;
        display: block;
        margin: 0 auto;
    }

    .id-card-front .signature-box span {
        font-size: 0.62rem;
        color: #64748B;
        border-top: 1px solid #CBD5E1;
        padding-top: 2px;
        display: block;
    }

    .id-card-back {
        background: #F8FAFC;
        padding: 1rem;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .id-card-back .back-terms {
        font-size: 0.65rem;
        color: #475569;
        line-height: 1.4;
        text-align: justify;
        background: #FFFFFF;
        padding: 0.6rem;
        border-radius: 6px;
        border: 1px solid #E2E8F0;
        margin-bottom: 0.5rem;
    }

    .id-card-back .qr-section {
        background: #FFFFFF;
        padding: 0.6rem;
        border-radius: 8px;
        border: 1px solid #E2E8F0;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.35rem;
    }

    .id-card-back .qr-img {
        width: 100px;
        height: 100px;
    }

    .id-card-back .barcode-mock {
        font-family: monospace;
        letter-spacing: 4px;
        font-weight: 800;
        font-size: 0.85rem;
        color: #1E293B;
        margin-top: 0.3rem;
    }

    .id-card-back .office-address-box {
        font-size: 0.62rem;
        color: #64748B;
        border-top: 1px solid #CBD5E1;
        padding-top: 0.4rem;
    }

    @media print {
        body * {
            visibility: hidden;
        }
        .id-card-wrapper, .id-card-wrapper * {
            visibility: visible;
        }
        .id-card-wrapper {
            position: absolute;
            left: 0;
            top: 0;
            margin: 0;
            gap: 20px;
        }
        .btn, .reporter-sidebar, .reporter-navbar, footer {
            display: none !important;
        }
    }
</style>

<div class="card card-custom mb-4">
    <div class="card-body py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-id-badge text-success me-2"></i> আমার ডিজিটাল প্রেস অ্যাক্রেডিটেশন আইডি কার্ড</h5>
            <div class="small text-muted">আইডি নম্বর: <strong><?= Helper::e($profile['reporter_id']) ?></strong></div>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success" onclick="window.print()" style="background: #0B6B3A; border-color: #0B6B3A;">
                <i class="fa-solid fa-print me-1"></i> আইডি কার্ড প্রিন্ট করুন
            </button>
            <a href="/reporter/verify/<?= $profile['reporter_id'] ?>" target="_blank" class="btn btn-outline-primary">
                <i class="fa-solid fa-qrcode me-1"></i> লাইভ কিউআর ভেরিফিকেশন পেজ
            </a>
        </div>
    </div>
</div>

<div class="id-card-wrapper">
    
    <!-- FRONT -->
    <div class="id-card id-card-front">
        <div class="card-header-band">
            <div style="font-size: 0.65rem; color: #86EFAC; font-weight: 700;">GOVT. REGD. DIGITAL MEDIA</div>
            <h4>GENZNEWZ</h4>
            <div class="card-type-tag">PRESS ACCREDITATION CARD</div>
        </div>

        <div class="photo-container">
            <img src="<?= Helper::e($profile['profile_photo'] ?: '/storage/uploads/reporters/default_reporter.jpg') ?>" class="reporter-photo" alt="Photo">
        </div>

        <div class="card-body-details">
            <div class="rep-name"><?= Helper::e($profile['full_name']) ?></div>
            <div class="rep-designation"><?= Helper::e($profile['designation']) ?></div>

            <table class="info-table">
                <tr>
                    <td class="label-td">প্রেস আইডি:</td>
                    <td class="val-td" style="color: #0B6B3A;"><?= Helper::e($profile['reporter_id']) ?></td>
                </tr>
                <tr>
                    <td class="label-td">কর্মক্ষেত্র / ব্যুরো:</td>
                    <td class="val-td"><?= Helper::e($profile['assigned_area']) ?></td>
                </tr>
                <tr>
                    <td class="label-td">রক্তের গ্রুপ:</td>
                    <td class="val-td"><?= Helper::e($profile['blood_group'] ?? 'N/A') ?></td>
                </tr>
                <tr>
                    <td class="label-td">জরুরি যোগাযোগ:</td>
                    <td class="val-td"><?= Helper::e($profile['phone']) ?></td>
                </tr>
                <tr>
                    <td class="label-td">বৈধতার মেয়াদ:</td>
                    <td class="val-td" style="color: <?= $isExpired ? '#DC2626' : '#0B6B3A' ?>;">
                        <?= Helper::formatBengaliDate($profile['valid_until']) ?>
                    </td>
                </tr>
            </table>
        </div>

        <div class="card-footer-band">
            <div style="font-size: 0.62rem; color: #64748B;">
                <strong>আইডি স্ট্যাটাস:</strong> 
                <span class="badge bg-<?= ($profile['id_card_status'] === 'active' && !$isExpired) ? 'success' : 'danger' ?>" style="font-size: 0.58rem;">
                    <?= ($profile['id_card_status'] === 'active' && !$isExpired) ? 'VERIFIED' : 'EXPIRED' ?>
                </span>
            </div>
            <div class="signature-box">
                <img src="/storage/uploads/reporters/editor_signature.png" alt="Signature" onerror="this.style.display='none'">
                <span>অনুমোদিত স্বাক্ষরকারী</span>
            </div>
        </div>
    </div>

    <!-- BACK -->
    <div class="id-card id-card-back">
        <div>
            <div class="fw-bold text-success mb-1" style="font-size: 0.82rem;">প্রেস কার্ডের সাধারণ নির্দেশনাবলী</div>
            <div class="back-terms">
                ১. এই প্রেস কার্ডটি GenzNewz সংবাদ সংস্থার সম্পত্তি। এটি হস্তান্তরযোগ্য নয়।<br>
                ২. কর্তব্যরত অবস্থায় কার্ডটি দৃশ্যমানভাবে প্রদর্শন করতে হবে।<br>
                ৩. কার্ডটি হারিয়ে গেলে অবিলম্বে প্রধান সম্পাদকীয় দপ্তর এবং স্থানীয় থানায় অবহিত করুন।<br>
                ৪. কার্ডের সত্যতা যাচাই করতে পাশের কিউআর কোড স্ক্যান করুন।
            </div>
        </div>

        <div class="qr-section">
            <span style="font-size: 0.65rem; color: #64748B; font-weight: 700;">স্ক্যান করে সত্যতা যাচাই করুন</span>
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= urlencode(APP_URL . '/reporter/verify/' . $profile['reporter_id']) ?>" class="qr-img" alt="QR Code">
            <div class="barcode-mock">||||| | |||| ||| |||||||</div>
            <span style="font-size: 0.68rem; font-family: monospace; font-weight: bold;"><?= Helper::e($profile['reporter_id']) ?></span>
        </div>

        <div class="office-address-box">
            <strong>সম্পাদকীয় দপ্তর:</strong> ১২/এ, আনন্দবাজার লেন, বি.বি.ডি বাগ, কলকাতা — ৭০০০০১<br>
            হেল্পলাইন: +91 33 2248 0000 | editor@genznewz.com
        </div>
    </div>

</div>

<?php require_once ROOT_PATH . '/reporter/views/layouts/footer.php'; ?>
