<?php require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();
$flashMessages = getFlash();
$currentPage = basename($_SERVER['PHP_SELF']);
$currentDir  = basename(dirname($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' - ' : '' ?>CozyStay Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/dashboard.css">
    <?php if (isset($extraCss)) echo $extraCss; ?>
</head>
<body>
<div class="dashboard-wrapper">

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="<?= SITE_URL ?>/dashboard/index.php" class="sidebar-brand">
                <i class="fas fa-hotel"></i>
                <span>CozyStay</span>
            </a>
            <button class="sidebar-toggle d-lg-none" id="sidebarClose">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="sidebar-nav">
            <div class="nav-section">
                <span class="nav-section-title">Principal</span>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="<?= SITE_URL ?>/dashboard/index.php" class="nav-link <?= ($currentPage === 'index.php' && $currentDir === 'dashboard') ? 'active' : '' ?>">
                            <i class="fas fa-chart-line"></i>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= SITE_URL ?>/dashboard/reservations/index.php" class="nav-link <?= ($currentDir === 'reservations') ? 'active' : '' ?>">
                            <i class="fas fa-calendar-check"></i>
                            <span>Réservations</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="nav-section">
                <span class="nav-section-title">Contenu</span>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="<?= SITE_URL ?>/dashboard/rooms/index.php" class="nav-link <?= ($currentDir === 'rooms') ? 'active' : '' ?>">
                            <i class="fas fa-bed"></i>
                            <span>Chambres</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= SITE_URL ?>/dashboard/services/index.php" class="nav-link <?= ($currentDir === 'services') ? 'active' : '' ?>">
                            <i class="fas fa-concierge-bell"></i>
                            <span>Services</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= SITE_URL ?>/dashboard/testimonials/index.php" class="nav-link <?= ($currentDir === 'testimonials') ? 'active' : '' ?>">
                            <i class="fas fa-quote-left"></i>
                            <span>Témoignages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= SITE_URL ?>/dashboard/gallery/index.php" class="nav-link <?= ($currentDir === 'gallery') ? 'active' : '' ?>">
                            <i class="fas fa-images"></i>
                            <span>Galerie</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= SITE_URL ?>/dashboard/hero/index.php" class="nav-link <?= ($currentDir === 'hero') ? 'active' : '' ?>">
                            <i class="fas fa-star"></i>
                            <span>Section Hero</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="nav-section">
                <span class="nav-section-title">Configuration</span>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="<?= SITE_URL ?>/dashboard/settings/index.php" class="nav-link <?= ($currentDir === 'settings') ? 'active' : '' ?>">
                            <i class="fas fa-cog"></i>
                            <span>Paramètres</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= SITE_URL ?>/dashboard/settings/contact.php" class="nav-link <?= ($currentPage === 'contact.php' && $currentDir === 'settings') ? 'active' : '' ?>">
                            <i class="fas fa-address-card"></i>
                            <span>Contact</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="nav-section mt-auto">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="<?= SITE_URL ?>/" class="nav-link" target="_blank">
                            <i class="fas fa-external-link-alt"></i>
                            <span>Voir le site</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= SITE_URL ?>/dashboard/logout.php" class="nav-link text-danger">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Déconnexion</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            <div class="navbar-search d-none d-md-block">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Rechercher...">
            </div>

            <div class="navbar-actions">
                <div class="dropdown">
                    <button class="navbar-user dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="user-avatar"><?= strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)) ?></div>
                        <span class="d-none d-md-inline"><?= e($_SESSION['admin_username'] ?? 'Admin') ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item text-danger" href="<?= SITE_URL ?>/dashboard/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="page-content">
            <?php if (!empty($flashMessages)): ?>
            <div class="messages-container">
                <?php foreach ($flashMessages as $msg): ?>
                <div class="alert alert-<?= $msg['type'] === 'success' ? 'success' : ($msg['type'] === 'error' ? 'danger' : $msg['type']) ?> alert-dismissible fade show">
                    <i class="fas <?= $msg['type'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?> me-2"></i>
                    <?= e($msg['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
