<?php
$pageTitle = "Réservations";
include __DIR__ . '/../includes/dash_header.php';

$db = getDB();
$statusFilter = $_GET['status'] ?? '';

$sql = "SELECT b.*, r.name as room_name, r.price_per_night FROM bookings b JOIN rooms r ON b.room_id = r.id";
$params = [];
if ($statusFilter) {
    $sql .= " WHERE b.status = ?";
    $params[] = $statusFilter;
}
$sql .= " ORDER BY b.created_at DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$bookings = $stmt->fetchAll();
?>

<div class="page-header">
    <div>
        <h1>Réservations</h1>
        <p>Gérez les réservations de votre hôtel</p>
    </div>
</div>

<!-- Filtres -->
<div class="dashboard-card mb-4">
    <div class="card-body">
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= SITE_URL ?>/dashboard/reservations/index.php" class="btn btn-sm <?= !$statusFilter ? 'btn-primary' : 'btn-outline-primary' ?>">Toutes</a>
            <a href="?status=pending" class="btn btn-sm <?= $statusFilter === 'pending' ? 'btn-warning text-dark' : 'btn-outline-warning' ?>">En attente</a>
            <a href="?status=confirmed" class="btn btn-sm <?= $statusFilter === 'confirmed' ? 'btn-success' : 'btn-outline-success' ?>">Confirmées</a>
            <a href="?status=cancelled" class="btn btn-sm <?= $statusFilter === 'cancelled' ? 'btn-danger' : 'btn-outline-danger' ?>">Annulées</a>
        </div>
    </div>
</div>

<div class="dashboard-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Chambre</th>
                        <th>Dates</th>
                        <th>Invités</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bookings)): ?>
                        <?php foreach ($bookings as $b): ?>
                        <tr>
                            <td class="text-muted">#<?= $b['id'] ?></td>
                            <td>
                                <div class="fw-medium"><?= e($b['first_name']) ?> <?= e($b['last_name']) ?></div>
                                <small class="text-muted"><?= e($b['email']) ?></small>
                            </td>
                            <td><?= e($b['room_name']) ?></td>
                            <td>
                                <small><?= formatDate($b['check_in']) ?></small><br>
                                <small class="text-muted">→ <?= formatDate($b['check_out']) ?></small>
                            </td>
                            <td><?= (int)$b['guests'] ?></td>
                            <td class="fw-medium">$<?= number_format(totalPrice($b['check_in'], $b['check_out'], $b['price_per_night']), 0) ?></td>
                            <td><span class="badge bg-<?= $b['status'] ?>"><?= statusLabel($b['status']) ?></span></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="<?= SITE_URL ?>/dashboard/reservations/detail.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-primary" title="Voir"><i class="fas fa-eye"></i></a>
                                    <a href="<?= SITE_URL ?>/dashboard/reservations/delete.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirm('Supprimer cette réservation?')"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-calendar fa-3x mb-3 d-block"></i>
                            Aucune réservation trouvée
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/dash_footer.php'; ?>
