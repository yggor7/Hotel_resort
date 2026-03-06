<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$contactInfo = getContactInfo();
$pageTitle = "Sitemap";
include __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 class="display-4">Sitemap</h1>
        <p class="lead">All pages of the CozyStay Resort website</p>
    </div>
</section>

<!-- Content -->
<section class="py-5">
    <div class="container">
        <div class="row">

            <div class="col-md-4 mb-5">
                <h5 class="fw-bold mb-3"><i class="fas fa-home me-2 text-muted"></i>Main Pages</h5>
                <ul class="list-unstyled ps-3">
                    <li class="mb-2"><a href="<?= SITE_URL ?>/">Home</a></li>
                    <li class="mb-2"><a href="<?= SITE_URL ?>/about.php">About Us</a></li>
                    <li class="mb-2"><a href="<?= SITE_URL ?>/contact.php">Contact</a></li>
                </ul>
            </div>

            <div class="col-md-4 mb-5">
                <h5 class="fw-bold mb-3"><i class="fas fa-bed me-2 text-muted"></i>Accommodation</h5>
                <ul class="list-unstyled ps-3">
                    <li class="mb-2"><a href="<?= SITE_URL ?>/rooms.php">Rooms & Suites</a></li>
                    <li class="mb-2"><a href="<?= SITE_URL ?>/booking.php">Book a Room</a></li>
                </ul>
            </div>

            <div class="col-md-4 mb-5">
                <h5 class="fw-bold mb-3"><i class="fas fa-concierge-bell me-2 text-muted"></i>Services</h5>
                <ul class="list-unstyled ps-3">
                    <li class="mb-2"><a href="<?= SITE_URL ?>/services.php">All Services</a></li>
                </ul>
            </div>

            <div class="col-md-4 mb-5">
                <h5 class="fw-bold mb-3"><i class="fas fa-file-alt me-2 text-muted"></i>Legal</h5>
                <ul class="list-unstyled ps-3">
                    <li class="mb-2"><a href="<?= SITE_URL ?>/privacy-policy.php">Privacy Policy</a></li>
                    <li class="mb-2"><a href="<?= SITE_URL ?>/terms.php">Terms of Use</a></li>
                    <li class="mb-2"><a href="<?= SITE_URL ?>/sitemap.php">Sitemap</a></li>
                </ul>
            </div>

        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
