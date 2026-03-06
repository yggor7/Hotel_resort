<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$db = getDB();
$id = (int)($_GET['id'] ?? 0);

if (!$id) redirect(SITE_URL . '/dashboard/reservations/index.php');

$stmt = $db->prepare("SELECT b.*, r.name as room_name, r.price_per_night, r.slug as room_slug FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.id = ?");
$stmt->execute([$id]);
$booking = $stmt->fetch();

if (!$booking) redirect(SITE_URL . '/dashboard/reservations/index.php');

// Changer le statut
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $newStatus = $_POST['status'];
    if (in_array($newStatus, ['pending', 'confirmed', 'cancelled'])) {
        $stmt = $db->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $id]);
        setFlash('success', 'Statut mis à jour avec succès.');
        redirect(SITE_URL . '/dashboard/reservations/detail.php?id=' . $id);
    }
}

$nights = totalNights($booking['check_in'], $booking['check_out']);
$total = totalPrice($booking['check_in'], $booking['check_out'], $booking['price_per_night']);
$pageTitle = "Détail réservation";
include __DIR__ . '/../includes/dash_header.php';
?>

<div class="page-header">
    <div>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= SITE_URL ?>/dashboard/reservations/index.php">Réservations</a></li><li class="breadcrumb-item active">Réservation #<?= $id ?></li></ol></nav>
        <h1>Réservation #<?= $id ?></h1>
    </div>
    <a href="<?= SITE_URL ?>/dashboard/reservations/index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Retour</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="card-header"><h5><i class="fas fa-user me-2"></i>Informations client</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Nom complet</label>
                        <p class="fw-medium mb-0"><?= e($booking['first_name']) ?> <?= e($booking['last_name']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Email</label>
                        <p class="fw-medium mb-0"><a href="mailto:<?= e($booking['email']) ?>"><?= e($booking['email']) ?></a></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Téléphone</label>
                        <p class="fw-medium mb-0"><?= e($booking['phone']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Date de réservation</label>
                        <p class="fw-medium mb-0"><?= formatDate($booking['created_at'], 'd M Y H:i') ?></p>
                    </div>
                    <?php if ($booking['message']): ?>
                    <div class="col-12">
                        <label class="text-muted small">Message</label>
                        <p class="fw-medium mb-0"><?= e($booking['message']) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="dashboard-card mt-4">
            <div class="card-header"><h5><i class="fas fa-bed me-2"></i>Détails du séjour</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Chambre</label>
                        <p class="fw-medium mb-0"><?= e($booking['room_name']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Invités</label>
                        <p class="fw-medium mb-0"><?= (int)$booking['guests'] ?> personne(s)</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Arrivée</label>
                        <p class="fw-medium mb-0"><?= formatDate($booking['check_in']) ?></p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Départ</label>
                        <p class="fw-medium mb-0"><?= formatDate($booking['check_out']) ?></p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Durée</label>
                        <p class="fw-medium mb-0"><?= $nights ?> nuit<?= $nights > 1 ? 's' : '' ?></p>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-medium">Prix par nuit:</span>
                    <span>$<?= number_format($booking['price_per_night'], 2) ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <span class="fw-bold fs-5">Total:</span>
                    <span class="fw-bold fs-5 text-primary">$<?= number_format($total, 2) ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="dashboard-card">
            <div class="card-header"><h5><i class="fas fa-tasks me-2"></i>Statut</h5></div>
            <div class="card-body">
                <div class="mb-3">
                    <span class="badge bg-<?= $booking['status'] ?> fs-6"><?= statusLabel($booking['status']) ?></span>
                </div>
                <form method="post">
                    <label class="form-label">Changer le statut</label>
                    <select name="status" class="form-select mb-3">
                        <option value="pending" <?= $booking['status'] === 'pending' ? 'selected' : '' ?>>En attente</option>
                        <option value="confirmed" <?= $booking['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmé</option>
                        <option value="cancelled" <?= $booking['status'] === 'cancelled' ? 'selected' : '' ?>>Annulé</option>
                    </select>
                    <button type="submit" class="btn btn-primary w-100">Mettre à jour</button>
                </form>
                <hr>
                <a href="<?= SITE_URL ?>/dashboard/reservations/delete.php?id=<?= $id ?>" class="btn btn-outline-danger w-100" onclick="return confirm('Supprimer cette réservation définitivement?')">
                    <i class="fas fa-trash me-2"></i>Supprimer
                </a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/dash_footer.php'; ?>
