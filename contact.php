<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDB();
$contactInfo = getContactInfo();
$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = sanitize($_POST['name'] ?? '');
    $email   = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone   = sanitize($_POST['phone'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    if (empty($name))    $errors[] = 'Le nom est requis.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalide.';
    if (empty($message)) $errors[] = 'Le message est requis.';

    if (empty($errors)) {
        // Ici vous pouvez envoyer un email ou sauvegarder en BDD
        // mail($contactInfo['email'], 'Contact: ' . $subject, "De: $name\nEmail: $email\nTel: $phone\n\n$message");
        $success = true;
        setFlash('success', 'Votre message a été envoyé avec succès! Nous vous répondrons bientôt.');
    }
}

$pageTitle = "Contact";
include __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 class="display-4">Nous Contacter</h1>
        <p class="lead">Nous sommes là pour vous aider</p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Information -->
            <div class="col-lg-5">
                <h2 class="mb-4">Informations de Contact</h2>

                <?php if ($contactInfo): ?>
                <div class="contact-info-item mb-4">
                    <div class="d-flex align-items-start">
                        <div class="contact-icon me-3">
                            <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h5>Adresse</h5>
                            <p class="text-muted"><?= e($contactInfo['address']) ?></p>
                        </div>
                    </div>
                </div>

                <div class="contact-info-item mb-4">
                    <div class="d-flex align-items-start">
                        <div class="contact-icon me-3">
                            <i class="fas fa-phone fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h5>Téléphone</h5>
                            <p class="text-muted"><?= e($contactInfo['phone']) ?></p>
                        </div>
                    </div>
                </div>

                <div class="contact-info-item mb-4">
                    <div class="d-flex align-items-start">
                        <div class="contact-icon me-3">
                            <i class="fas fa-envelope fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h5>Email</h5>
                            <p class="text-muted"><?= e($contactInfo['email']) ?></p>
                        </div>
                    </div>
                </div>

                <div class="social-links mt-4">
                    <h5 class="mb-3">Suivez-nous</h5>
                    <div class="d-flex gap-3">
                        <?php if ($contactInfo['facebook']): ?>
                        <a href="<?= e($contactInfo['facebook']) ?>" class="btn btn-outline-primary" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <?php endif; ?>
                        <?php if ($contactInfo['instagram']): ?>
                        <a href="<?= e($contactInfo['instagram']) ?>" class="btn btn-outline-primary" target="_blank"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                        <?php if ($contactInfo['twitter']): ?>
                        <a href="<?= e($contactInfo['twitter']) ?>" class="btn btn-outline-primary" target="_blank"><i class="fab fa-twitter"></i></a>
                        <?php endif; ?>
                        <?php if ($contactInfo['youtube']): ?>
                        <a href="<?= e($contactInfo['youtube']) ?>" class="btn btn-outline-primary" target="_blank"><i class="fab fa-youtube"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3 class="mb-4">Envoyez-nous un message</h3>

                        <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <form method="post" id="contactForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nom complet *</label>
                                    <input type="text" class="form-control" name="name" value="<?= e($_POST['name'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email *</label>
                                    <input type="email" class="form-control" name="email" value="<?= e($_POST['email'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" name="phone" value="<?= e($_POST['phone'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Sujet</label>
                                    <select class="form-select" name="subject">
                                        <option value="general">Question générale</option>
                                        <option value="booking">Réservation</option>
                                        <option value="feedback">Commentaire</option>
                                        <option value="other">Autre</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Message *</label>
                                    <textarea class="form-control" name="message" rows="5" required><?= e($_POST['message'] ?? '') ?></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-paper-plane me-2"></i>Envoyer le message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Notre Localisation</h2>
        <?php if ($contactInfo && $contactInfo['google_maps_embed']): ?>
        <div class="ratio ratio-21x9">
            <?= $contactInfo['google_maps_embed'] ?>
        </div>
        <?php else: ?>
        <div class="ratio ratio-21x9">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.8354345093747!2d144.95373631531677!3d-37.81720997975171!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad65d43f3f2f3f3%3A0xf0456760531e310!2sFederation%20Square!5e0!3m2!1sen!2sau!4v1234567890123!5m2!1sen!2sau"
                style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
