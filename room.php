<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDB();
$slug = $_GET['slug'] ?? '';

if (!$slug) {
    redirect(SITE_URL . '/rooms.php');
}

$stmt = $db->prepare("SELECT * FROM rooms WHERE slug = ? AND is_active = 1");
$stmt->execute([$slug]);
$room = $stmt->fetch();

if (!$room) {
    redirect(SITE_URL . '/rooms.php');
}

// Images de la chambre
$stmtImgs = $db->prepare("SELECT * FROM room_images WHERE room_id = ? ORDER BY order_position ASC");
$stmtImgs->execute([$room['id']]);
$roomImages = $stmtImgs->fetchAll();

// Chambres liées
$stmtRelated = $db->prepare("SELECT * FROM rooms WHERE is_active = 1 AND id != ? ORDER BY order_position ASC LIMIT 3");
$stmtRelated->execute([$room['id']]);
$relatedRooms = $stmtRelated->fetchAll();

$contactInfo = getContactInfo();
$pageTitle = $room['name'];
include __DIR__ . '/includes/header.php';
?>

<!-- Room Hero -->
<section class="room-hero">
    <img src="<?= imageUrl($room['image']) ?>" alt="<?= e($room['name']) ?>" class="img-fluid w-100">
</section>

<!-- Room Details -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="display-5 mb-4"><?= e($room['name']) ?></h1>

                <div class="room-features-detailed mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="feature-box p-3 text-center border rounded">
                                <i class="fas fa-expand-arrows-alt fa-2x mb-2 text-primary"></i>
                                <h6>Superficie</h6>
                                <p class="mb-0"><?= (int)$room['size'] ?> m²</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="feature-box p-3 text-center border rounded">
                                <i class="fas fa-user fa-2x mb-2 text-primary"></i>
                                <h6>Invités</h6>
                                <p class="mb-0"><?= (int)$room['guests'] ?> Guests</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="feature-box p-3 text-center border rounded">
                                <i class="fas fa-bed fa-2x mb-2 text-primary"></i>
                                <h6>Lits</h6>
                                <p class="mb-0"><?= (int)$room['beds'] ?> <?= e($room['bed_type']) ?></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="feature-box p-3 text-center border rounded">
                                <i class="fas fa-dollar-sign fa-2x mb-2 text-primary"></i>
                                <h6>Prix/Nuit</h6>
                                <p class="mb-0">$<?= number_format($room['price_per_night'], 2) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="room-description mb-5">
                    <h3 class="h4 mb-3">Description</h3>
                    <?= $room['description'] ?>
                </div>

                <!-- Room Gallery -->
                <?php if (!empty($roomImages)): ?>
                <div class="room-gallery mb-5">
                    <h3 class="h4 mb-3">Galerie Photos</h3>
                    <div class="row g-3">
                        <?php foreach ($roomImages as $img): ?>
                        <div class="col-md-4">
                            <img src="<?= imageUrl($img['image']) ?>" alt="<?= e($img['caption']) ?>" class="img-fluid rounded">
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Booking Sidebar -->
            <div class="col-lg-4">
                <div class="booking-sidebar bg-light p-4 rounded sticky-top">
                    <h4 class="mb-4">Réserver cette chambre</h4>
                    <div class="price-box mb-4">
                        <span class="h2 text-primary">$<?= number_format($room['price_per_night'], 2) ?></span>
                        <span class="text-muted">/ nuit</span>
                    </div>
                    <a href="<?= SITE_URL ?>/booking.php?slug=<?= e($room['slug']) ?>" class="btn btn-primary btn-lg w-100 mb-3">Réserver maintenant</a>

                    <div class="contact-info mt-4">
                        <h6 class="mb-3">Besoin d'aide ?</h6>
                        <?php if ($contactInfo): ?>
                        <p class="small mb-2"><i class="fas fa-phone me-2"></i><?= e($contactInfo['phone']) ?></p>
                        <p class="small mb-0"><i class="fas fa-envelope me-2"></i><?= e($contactInfo['email']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Rooms -->
<?php if (!empty($relatedRooms)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="mb-4">Autres chambres</h3>
        <div class="row g-4">
            <?php foreach ($relatedRooms as $r): ?>
            <div class="col-md-4">
                <div class="room-card h-100">
                    <img src="<?= imageUrl($r['image']) ?>" alt="<?= e($r['name']) ?>" class="img-fluid">
                    <div class="p-3">
                        <h5 class="mb-3"><?= e($r['name']) ?></h5>
                        <p class="text-muted small"><?= truncateWords($r['description'], 15) ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-primary fw-bold">$<?= number_format($r['price_per_night'], 2) ?>/nuit</span>
                            <a href="<?= SITE_URL ?>/room.php?slug=<?= e($r['slug']) ?>" class="btn btn-sm btn-outline-dark">Voir</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
