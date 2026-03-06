<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDB();
$services = $db->query("SELECT * FROM services WHERE is_active = 1 ORDER BY order_position ASC")->fetchAll();
$contactInfo = getContactInfo();

$pageTitle = "Nos Services";
include __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 class="display-4">Nos Services</h1>
        <p class="lead">Découvrez l'excellence à chaque instant</p>
    </div>
</section>

<!-- Services Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $service): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="service-card h-100">
                        <div class="service-image mb-4">
                            <img src="<?= imageUrl($service['image']) ?>" alt="<?= e($service['title']) ?>" class="img-fluid rounded">
                        </div>
                        <h3 class="h4 mb-3"><?= e($service['title']) ?></h3>
                        <div class="service-description">
                            <?= $service['description'] ?>
                        </div>
                        <a href="<?= SITE_URL ?>/service.php?slug=<?= e($service['slug']) ?>" class="btn btn-outline-primary mt-3">
                            En savoir plus <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
            <div class="col-12 text-center">
                <p class="lead text-muted">Aucun service disponible pour le moment.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
