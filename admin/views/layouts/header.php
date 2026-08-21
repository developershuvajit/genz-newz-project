<?php
/**
 * GenzNewz — Admin Header Layout
 * Uses Bootstrap 5 + Font Awesome 6 + SweetAlert2
 */
$currentUser = Auth::user();
$unreadNotifications = Notification::getUnreadForUser(Auth::id());
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Helper::e($pageTitle ?? 'অ্যাডমিন প্যানেল') ?> — GenzNewz</title>

    <!-- Google Bengali & Sans Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700&family=Noto+Sans+Bengali:wght@400;500;600;700&family=Noto+Serif+Bengali:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Custom Admin Styles -->
    <style>
        :root {
            --gnz-primary: #0B6B3A;
            --gnz-primary-dark: #064D2B;
            --gnz-primary-light: #E8F5E9;
            --gnz-accent-gold: #FFD700;
            --gnz-sidebar-width: 260px;
        }

        body {
            font-family: 'Noto Sans Bengali', 'Hind Siliguri', sans-serif;
            background-color: #F1F5F9;
            color: #1E293B;
            min-height: 100vh;
        }

        .admin-sidebar {
            width: var(--gnz-sidebar-width);
            background: #064D2B;
            color: #E2E8F0;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s;
        }

        .admin-sidebar .sidebar-brand {
            padding: 1.25rem 1.5rem;
            background: #04361E;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand-title {
            font-family: 'Noto Serif Bengali', serif;
            font-size: 1.4rem;
            font-weight: 800;
            color: #FFD700;
            line-height: 1.1;
        }

        .sidebar-menu {
            list-style: none;
            padding: 1rem 0;
            margin: 0;
        }

        .sidebar-header-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #86EFAC;
            padding: 0.75rem 1.5rem 0.25rem;
            font-weight: 700;
        }

        .sidebar-item a {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.7rem 1.5rem;
            color: #E2E8F0;
            text-decoration: none;
            font-size: 0.92rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .sidebar-item a:hover,
        .sidebar-item.active a {
            background: rgba(255,255,255,0.12);
            color: #FFFFFF;
            border-left: 4px solid #FFD700;
        }

        .sidebar-item a i {
            width: 20px;
            font-size: 1.05rem;
            text-align: center;
        }

        .admin-main-wrapper {
            margin-left: var(--gnz-sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .admin-navbar {
            background: #FFFFFF;
            border-bottom: 1px solid #E2E8F0;
            padding: 0.75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 990;
        }

        .admin-content-body {
            padding: 1.75rem;
            flex-grow: 1;
        }

        .card-custom {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .btn-gnz-primary {
            background: #0B6B3A;
            border-color: #0B6B3A;
            color: #FFFFFF;
            font-weight: 600;
        }

        .btn-gnz-primary:hover {
            background: #064D2B;
            border-color: #064D2B;
            color: #FFFFFF;
        }

        .badge-pill-status {
            padding: 0.35rem 0.65rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        @media (max-width: 992px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-main-wrapper {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Admin Sidebar -->
    <?php require_once ROOT_PATH . '/admin/views/layouts/sidebar.php'; ?>

    <!-- Main Wrapper -->
    <div class="admin-main-wrapper flex-grow-1">
        
        <!-- Admin Top Navigation Bar -->
        <nav class="admin-navbar d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary d-lg-none" id="btn-sidebar-toggle">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h5 class="mb-0 fw-bold text-dark"><?= Helper::e($pageTitle ?? 'ড্যাশবোর্ড') ?></h5>
            </div>

            <div class="d-flex align-items-center gap-3">
                <a href="/" target="_blank" class="btn btn-sm btn-outline-success">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> ওয়েবসাইট দেখুন
                </a>

                <!-- Notifications Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light position-relative rounded-circle p-2" type="button" data-bs-toggle="dropdown" style="width: 40px; height: 40px;">
                        <i class="fa-regular fa-bell text-secondary"></i>
                        <?php if (count($unreadNotifications) > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                <?= count($unreadNotifications) ?>
                            </span>
                        <?php endif; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm p-2" style="width: 300px; font-size: 0.85rem;">
                        <li class="p-2 border-bottom fw-bold text-dark d-flex justify-content-between">
                            <span>বিজ্ঞপ্তিসমূহ</span>
                            <a href="/admin/notifications" class="text-success text-decoration-none small">সব দেখুন</a>
                        </li>
                        <?php if (empty($unreadNotifications)): ?>
                            <li class="p-3 text-center text-muted">কোনো নতুন বিজ্ঞপ্তি নেই</li>
                        <?php else: ?>
                            <?php foreach (array_slice($unreadNotifications, 0, 4) as $notif): ?>
                                <li class="p-2 border-bottom">
                                    <a href="<?= Helper::e($notif['link'] ?: '/admin/notifications') ?>" class="text-dark text-decoration-none">
                                        <div class="fw-semibold text-truncate"><?= Helper::e($notif['title']) ?></div>
                                        <div class="text-muted small text-truncate"><?= Helper::e($notif['message']) ?></div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Admin Profile Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light d-flex align-items-center gap-2 p-1 pe-3 rounded-pill border" type="button" data-bs-toggle="dropdown">
                        <img src="<?= Helper::e($currentUser['profile_image'] ?: '/storage/uploads/reporters/default_reporter.jpg') ?>" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;" alt="Avatar">
                        <span class="fw-semibold small d-none d-md-inline"><?= Helper::e($currentUser['name']) ?></span>
                        <i class="fa-solid fa-angle-down text-muted small"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="font-size: 0.9rem;">
                        <li><a class="dropdown-item" href="/admin/profile"><i class="fa-solid fa-user-gear me-2 text-muted"></i> প্রোফাইল সেটিংস</a></li>
                        <li><a class="dropdown-item" href="/admin/settings"><i class="fa-solid fa-sliders me-2 text-muted"></i> পোর্টাল কনফিগারেশন</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/admin/logout"><i class="fa-solid fa-right-from-bracket me-2"></i> লগআউট</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Flash messages -->
        <?php if ($flashSuccess = Session::getFlash('success')): ?>
            <div class="mx-4 mt-3 alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> <?= Helper::e($flashSuccess) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($flashError = Session::getFlash('error')): ?>
            <div class="mx-4 mt-3 alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i> <?= Helper::e($flashError) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Content Area -->
        <main class="admin-content-body">
