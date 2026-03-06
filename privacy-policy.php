<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$contactInfo = getContactInfo();
$pageTitle = "Privacy Policy";
include __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 class="display-4">Privacy Policy</h1>
        <p class="lead">How we collect, use, and protect your information</p>
    </div>
</section>

<!-- Content -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <p class="text-muted mb-4">Last updated: <?= date('F Y') ?></p>

                <h4 class="mb-3">1. Information We Collect</h4>
                <p>When you make a reservation or contact us, we may collect the following personal information:</p>
                <ul>
                    <li>Full name and contact details (email address, phone number)</li>
                    <li>Billing and payment information</li>
                    <li>Stay preferences and special requests</li>
                    <li>Newsletter subscription data (email only)</li>
                </ul>

                <h4 class="mb-3 mt-5">2. How We Use Your Information</h4>
                <p>We use the information we collect to:</p>
                <ul>
                    <li>Process and manage your reservations</li>
                    <li>Communicate with you regarding your stay</li>
                    <li>Send promotional offers and newsletters (only if you opted in)</li>
                    <li>Improve our services and website experience</li>
                    <li>Comply with legal obligations</li>
                </ul>

                <h4 class="mb-3 mt-5">3. Data Sharing</h4>
                <p>We do not sell, trade, or otherwise transfer your personal information to third parties, except as required to provide our services (e.g., payment processors) or as required by law.</p>

                <h4 class="mb-3 mt-5">4. Data Security</h4>
                <p>We implement appropriate technical and organizational measures to protect your personal data against unauthorized access, alteration, disclosure, or destruction.</p>

                <h4 class="mb-3 mt-5">5. Cookies</h4>
                <p>Our website uses cookies to enhance your browsing experience. You can disable cookies through your browser settings; however, this may affect certain features of the website.</p>

                <h4 class="mb-3 mt-5">6. Your Rights</h4>
                <p>You have the right to:</p>
                <ul>
                    <li>Access the personal data we hold about you</li>
                    <li>Request correction of inaccurate data</li>
                    <li>Request deletion of your personal data</li>
                    <li>Withdraw consent for newsletter communications at any time</li>
                </ul>

                <h4 class="mb-3 mt-5">7. Contact Us</h4>
                <p>If you have any questions about this Privacy Policy or how we handle your data, please contact us:</p>
                <ul class="list-unstyled">
                    <li><i class="fas fa-envelope me-2 text-muted"></i><?= $contactInfo ? e($contactInfo['email']) : 'reservation@cozystay.com' ?></li>
                    <li><i class="fas fa-phone me-2 text-muted"></i><?= $contactInfo ? e($contactInfo['phone']) : '+41 22 345 67 88' ?></li>
                    <li><i class="fas fa-map-marker-alt me-2 text-muted"></i><?= $contactInfo ? e($contactInfo['address']) : '73120 Courchevel 1850, France' ?></li>
                </ul>

            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
