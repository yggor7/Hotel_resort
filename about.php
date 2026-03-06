<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDB();
$hero = $db->query("SELECT * FROM hero_sections WHERE is_active = 1 ORDER BY order_position ASC LIMIT 1")->fetch();
$services = $db->query("SELECT * FROM services WHERE is_active = 1 ORDER BY order_position ASC LIMIT 3")->fetchAll();
$testimonials = $db->query("SELECT * FROM testimonials WHERE is_active = 1 ORDER BY order_position ASC")->fetchAll();
$contactInfo = getContactInfo();

$pageTitle = "À Propos";
include __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 class="display-4">À Propos de Nous</h1>
        <p class="lead">Découvrez notre histoire et notre passion</p>
    </div>
</section>

<!-- About Content -->
<section class="py-5">
    <div class="container">
        <?php if ($hero): ?>
        <div class="row align-items-center mb-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="display-6 mb-4"><?= e($hero['title']) ?></h2>
                <p class="lead"><?= e($hero['subtitle']) ?></p>
                <div class="mt-4">
                    <?= $hero['description'] ?>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="<?= SITE_URL ?>/assets/images/paulo-evangelista-iJelGtuc52g-unsplash.jpg" alt="About" class="img-fluid rounded shadow">
            </div>
        </div>
        <?php endif; ?>

        <div class="row py-5">
            <div class="col-12">
                <h2 class="display-6 text-center mb-5">Notre Mission</h2>
                <div class="row g-4">
                    <div class="col-md-4 text-center">
                        <div class="p-4">
                            <i class="fas fa-heart fa-3x text-primary mb-3"></i>
                            <h4>Hospitalité</h4>
                            <p class="text-muted">Offrir une expérience chaleureuse et personnalisée à chaque invité.</p>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="p-4">
                            <i class="fas fa-leaf fa-3x text-primary mb-3"></i>
                            <h4>Durabilité</h4>
                            <p class="text-muted">Préserver notre environnement paradisiaque pour les générations futures.</p>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="p-4">
                            <i class="fas fa-star fa-3x text-primary mb-3"></i>
                            <h4>Excellence</h4>
                            <p class="text-muted">Maintenir les plus hauts standards de qualité dans tous nos services.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Overview -->
<?php if (!empty($services)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="display-6 text-center mb-5">Nos Services</h2>
        <div class="row g-4">
            <?php foreach ($services as $service): ?>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="<?= imageUrl($service['image']) ?>" class="card-img-top" alt="<?= e($service['title']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= e($service['title']) ?></h5>
                        <p class="card-text text-muted"><?= truncateWords($service['description'], 20) ?></p>
                        <a href="<?= SITE_URL ?>/service.php?slug=<?= e($service['slug']) ?>" class="btn btn-link p-0">En savoir plus →</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Testimonials -->
<?php if (!empty($testimonials)): ?>
<section class="py-5">
    <div class="container">
        <h2 class="display-6 text-center mb-5">Ce que disent nos clients</h2>
        <div class="row g-4">
            <?php foreach ($testimonials as $testimonial): ?>
            <div class="col-md-6">
                <div class="testimonial-card p-4 bg-light rounded h-100">
                    <div class="d-flex align-items-center mb-3">
                        <?php if ($testimonial['image']): ?>
                        <img src="<?= imageUrl($testimonial['image']) ?>" alt="<?= e($testimonial['author']) ?>" class="rounded-circle me-3" style="width:60px;height:60px;object-fit:cover;">
                        <?php endif; ?>
                        <div>
                            <h6 class="mb-0"><?= e($testimonial['author']) ?></h6>
                            <small class="text-muted"><?= e($testimonial['source']) ?></small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <?= renderStars($testimonial['rating']) ?>
                    </div>
                    <p class="fst-italic">"<?= e($testimonial['content']) ?>"</p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="display-5 mb-4">Prêt à vivre l'expérience?</h2>
        <p class="lead mb-4">Réservez votre séjour de rêve dès aujourd'hui</p>
        <a href="<?= SITE_URL ?>/rooms.php" class="btn btn-light btn-lg">Voir nos chambres</a>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
