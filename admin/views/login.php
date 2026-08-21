<?php
/**
 * GenzNewz — Admin Login View
 */
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>সুপার অ্যাডমিন লগইন — GenzNewz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&family=Noto+Serif+Bengali:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            background: linear-gradient(135deg, #064D2B, #0B6B3A);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.25);
            max-width: 440px;
            width: 100%;
            padding: 2.5rem;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <div class="d-inline-flex p-3 rounded-circle bg-success-subtle text-success mb-2">
            <i class="fa-solid fa-shield-halved fs-2"></i>
        </div>
        <h3 class="fw-bold mb-1" style="font-family: 'Noto Serif Bengali', serif; color: #064D2B;">GENZNEWZ</h3>
        <p class="text-muted small">সুপার অ্যাডমিনিস্ট্রেটর পোর্টাল</p>
    </div>

    <!-- Quick Demo Filler -->
    <div class="alert alert-light border small mb-3 p-2 text-center">
        <span class="text-muted">ডেমো অ্যাকাউন্ট:</span> <strong>admin@genznewz.com</strong> | পাস: <strong>admin123</strong>
        <button type="button" class="btn btn-sm btn-outline-success mt-1 w-100 py-1" onclick="document.getElementById('email').value='admin@genznewz.com'; document.getElementById('password').value='admin123';">
            ১-ক্লিকে তথ্য পূরণ করুন
        </button>
    </div>

    <?php if ($flashError = Session::getFlash('error')): ?>
        <div class="alert alert-danger p-2 small"><?= Helper::e($flashError) ?></div>
    <?php endif; ?>

    <form action="/admin/login" method="POST">
        <?= CSRF::field() ?>
        
        <div class="mb-3">
            <label class="form-label small fw-semibold">অ্যাডমিন ইমেইল</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="fa-regular fa-envelope text-muted"></i></span>
                <input type="email" name="email" id="email" class="form-control" placeholder="admin@genznewz.com" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label small fw-semibold">পাসওয়ার্ড</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="fa-solid fa-lock text-muted"></i></span>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit" class="btn btn-success w-100 py-2 fw-bold" style="background-color: #0B6B3A; border-color: #0B6B3A;">
            সুরক্ষিত লগইন করুন
        </button>
    </form>

    <div class="text-center mt-4">
        <a href="/" class="text-muted small text-decoration-none"><i class="fa-solid fa-arrow-left"></i> পাবলিক নিউজ সাইটে ফিরে যান</a>
    </div>
</div>

</body>
</html>
