<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDB();
$slug = $_GET['slug'] ?? '';

if (!$slug) {
    redirect(SITE_URL . '/services.php');
}

$stmt = $db->prepare("SELECT * FROM services WHERE slug = ? AND is_active = 1");
$stmt->execute([$slug]);
$service = $stmt->fetch();

if (!$service) {
    redirect(SITE_URL . '/services.php');
}

$contactInfo = getContactInfo();
$pageTitle = $service['title'];
include __DIR__ . '/includes/header.php';
?>

<!-- Service Hero -->
<section class="service-hero">
    <img src="<?= imageUrl($service['image']) ?>" alt="<?= e($service['title']) ?>" class="img-fluid w-100" style="margin-top: 76px; max-height: 500px; object-fit: cover;">
</section>

<!-- Service Details -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h1 class="display-5 mb-4"><?= e($service['title']) ?></h1>

                <div class="service-content">
                    <?= $service['description'] ?>
                </div>

                <div class="mt-5">
                    <a href="<?= SITE_URL ?>/contact.php" class="btn btn-primary btn-lg me-3">Nous contacter</a>
                    <a href="<?= SITE_URL ?>/services.php" class="btn btn-outline-secondary btn-lg">Retour aux services</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
