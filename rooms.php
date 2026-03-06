<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDB();
$rooms = $db->query("SELECT * FROM rooms WHERE is_active = 1 ORDER BY order_position ASC")->fetchAll();
$contactInfo = getContactInfo();

$pageTitle = "Nos Chambres";
include __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 class="display-4">Nos Chambres</h1>
        <p class="lead">Découvrez nos hébergements d'exception</p>
    </div>
</section>

<!-- Rooms List -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <?php if (!empty($rooms)): ?>
                <?php foreach ($rooms as $room): ?>
                <div class="col-md-6">
                    <div class="room-card-large">
                        <div class="row g-0">
                            <div class="col-md-6">
                                <img src="<?= imageUrl($room['image']) ?>" alt="<?= e($room['name']) ?>" class="img-fluid h-100" style="object-fit:cover;">
                            </div>
                            <div class="col-md-6">
                                <div class="p-4">
                                    <h3 class="h4 mb-3"><?= e($room['name']) ?></h3>
                                    <div class="room-features mb-3">
                                        <div class="mb-2">
                                            <i class="fas fa-expand-arrows-alt me-2"></i>
                                            <span><?= (int)$room['size'] ?> m²</span>
                                        </div>
                                        <div class="mb-2">
                                            <i class="fas fa-user me-2"></i>
                                            <span><?= (int)$room['guests'] ?> Guests</span>
                                        </div>
                                        <div class="mb-2">
                                            <i class="fas fa-bed me-2"></i>
                                            <span><?= (int)$room['beds'] ?> <?= e($room['bed_type']) ?></span>
                                        </div>
                                    </div>
                                    <p class="text-muted"><?= truncateWords($room['description'], 25) ?></p>
                                    <div class="d-flex justify-content-between align-items-center mt-4">
                                        <div class="price">
                                            <span class="h5 text-primary">$<?= number_format($room['price_per_night'], 2) ?></span>
                                            <span class="text-muted small">/nuit</span>
                                        </div>
                                        <a href="<?= SITE_URL ?>/room.php?slug=<?= e($room['slug']) ?>" class="btn btn-dark">Voir détails</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-bed fa-4x text-muted mb-4"></i>
                <p class="lead text-muted">Aucune chambre disponible pour le moment.</p>
                <p class="text-muted">Revenez bientôt ou contactez-nous pour plus d'informations.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
