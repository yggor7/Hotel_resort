<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$contactInfo = getContactInfo();
$pageTitle = "Terms of Use";
include __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 class="display-4">Terms of Use</h1>
        <p class="lead">Please read these terms carefully before using our services</p>
    </div>
</section>

<!-- Content -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <p class="text-muted mb-4">Last updated: <?= date('F Y') ?></p>

                <h4 class="mb-3">1. Acceptance of Terms</h4>
                <p>By accessing and using the CozyStay Resort website and services, you agree to be bound by these Terms of Use. If you do not agree to these terms, please do not use our website or services.</p>

                <h4 class="mb-3 mt-5">2. Reservations & Bookings</h4>
                <p>All reservations are subject to availability. A reservation is only confirmed upon receipt of a confirmation email from CozyStay Resort. We reserve the right to cancel any booking that cannot be fulfilled.</p>

                <h4 class="mb-3 mt-5">3. Check-In & Check-Out</h4>
                <ul>
                    <li><strong>Check-in:</strong> 3:00 PM (15:00)</li>
                    <li><strong>Check-out:</strong> 11:00 AM (11:00)</li>
                    <li>Early check-in and late check-out are subject to availability and may incur additional charges.</li>
                </ul>

                <h4 class="mb-3 mt-5">4. Cancellation Policy</h4>
                <p>Cancellations made more than 48 hours before the scheduled arrival date are fully refunded. Cancellations within 48 hours of arrival are subject to a one-night charge. No-shows will be charged the full stay.</p>

                <h4 class="mb-3 mt-5">5. Payment</h4>
                <p>Payment is required at check-in unless otherwise agreed. We accept major credit cards and bank transfers. All prices are inclusive of applicable taxes.</p>

                <h4 class="mb-3 mt-5">6. Guest Conduct</h4>
                <p>Guests are expected to behave in a manner respectful to staff and other guests. The management reserves the right to request that any guest causing disturbance vacate the premises without refund.</p>

                <h4 class="mb-3 mt-5">7. Liability</h4>
                <p>CozyStay Resort is not liable for loss of personal property, theft, or injury to guests or their belongings, except where caused by gross negligence on our part. We recommend guests use the in-room safe for valuables.</p>

                <h4 class="mb-3 mt-5">8. Use of Website</h4>
                <p>The content of this website is for general information and use only. You may not reproduce, distribute, or commercially exploit any content from this site without our prior written consent.</p>

                <h4 class="mb-3 mt-5">9. Modifications</h4>
                <p>CozyStay Resort reserves the right to modify these Terms of Use at any time. Changes will be effective immediately upon posting to the website.</p>

                <h4 class="mb-3 mt-5">10. Contact</h4>
                <p>For any questions regarding these terms, please contact us:</p>
                <ul class="list-unstyled">
                    <li><i class="fas fa-envelope me-2 text-muted"></i><?= $contactInfo ? e($contactInfo['email']) : 'reservation@cozystay.com' ?></li>
                    <li><i class="fas fa-phone me-2 text-muted"></i><?= $contactInfo ? e($contactInfo['phone']) : '+41 22 345 67 88' ?></li>
                </ul>

            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
