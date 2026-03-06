<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
$contactInfo = getContactInfo();
$siteSettings = getSiteSettings();
$flashMessages = getFlash();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' - ' : '' ?>CozyStay - Boutique Private Island Resort</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">

    <?php if (isset($extraCss)) echo $extraCss; ?>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar d-none d-lg-block">
        <div class="container-fluid px-5">
            <div class="d-flex justify-content-end align-items-center">
                <span class="top-bar-phone me-4">
                    <span class="text-muted">Tel:</span>
                    <?= $contactInfo ? e($contactInfo['phone']) : '+41 22 345 67 88' ?>
                </span>
                <div class="language-switcher me-4">
                    <a href="#" class="active">EN</a> / <a href="#">FR</a>
                </div>
                <a href="<?= SITE_URL ?>/rooms.php" class="btn btn-book-now">Book Now</a>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark" id="mainNavbar">
        <div class="container-fluid px-4 px-lg-5">
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Left Navigation -->
            <div class="collapse navbar-collapse order-lg-1" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="<?= SITE_URL ?>/" id="homeDropdown" role="button" data-bs-toggle="dropdown">HOME</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= SITE_URL ?>/">Island Resort</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="<?= SITE_URL ?>/rooms.php" id="stayDropdown" role="button" data-bs-toggle="dropdown">STAY</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= SITE_URL ?>/rooms.php">Rooms</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-bs-toggle="dropdown">PAGES</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= SITE_URL ?>/about.php">About</a></li>
                            <li><a class="dropdown-item" href="<?= SITE_URL ?>/services.php">Services</a></li>
                            <li><a class="dropdown-item" href="<?= SITE_URL ?>/contact.php">Contact</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= SITE_URL ?>/contact.php">CONTACT</a>
                    </li>
                </ul>
            </div>

            <!-- Center Logo -->
            <a class="navbar-brand order-lg-2" href="<?= SITE_URL ?>/">
                <div class="logo-container text-center">
                    <span class="logo-text">COZYSTAY</span>
                    <div class="logo-stars">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                </div>
            </a>

            <!-- Right Navigation -->
            <div class="collapse navbar-collapse order-lg-3 justify-content-end" id="navbarNavRight">
                <ul class="navbar-nav"></ul>
            </div>

            <!-- Mobile -->
            <div class="d-lg-none">
                <a href="<?= SITE_URL ?>/rooms.php" class="btn btn-book-now-mobile">Book</a>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (!empty($flashMessages)): ?>
    <div class="messages-container">
        <?php foreach ($flashMessages as $msg): ?>
        <div class="alert alert-<?= $msg['type'] === 'success' ? 'success' : ($msg['type'] === 'error' ? 'danger' : $msg['type']) ?> alert-dismissible fade show" role="alert">
            <?= e($msg['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
