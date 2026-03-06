<?php
$pageTitle = "Tableau de bord";
include __DIR__ . '/includes/dash_header.php';

$db = getDB();

// Stats
$totalRooms      = $db->query("SELECT COUNT(*) FROM rooms WHERE is_active = 1")->fetchColumn();
$totalBookings   = $db->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$pendingBookings = $db->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'")->fetchColumn();

// Revenus du mois
$monthStart = date('Y-m-01');
$monthEnd   = date('Y-m-t');
$stmt = $db->prepare("SELECT SUM((DATEDIFF(check_out, check_in)) * r.price_per_night) FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.status != 'cancelled' AND b.created_at BETWEEN ? AND ?");
$stmt->execute([$monthStart, $monthEnd . ' 23:59:59']);
$monthlyRevenue = $stmt->fetchColumn() ?: 0;

// Dernières réservations
$recentBookings = $db->query("SELECT b.*, r.name as room_name, r.price_per_night FROM bookings b JOIN rooms r ON b.room_id = r.id ORDER BY b.created_at DESC LIMIT 10")->fetchAll();

// Chambres populaires
$roomsStats = $db->query("SELECT r.name, COUNT(b.id) as booking_count FROM rooms r LEFT JOIN bookings b ON r.id = b.room_id GROUP BY r.id, r.name ORDER BY booking_count DESC LIMIT 5")->fetchAll();
?>

<div class="page-header">
    <div>
        <h1>Tableau de bord</h1>
        <p>Bienvenue, <?= e($_SESSION['admin_username'] ?? 'Admin') ?>! Voici un aperçu de votre hôtel.</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon bg-primary-light"><i class="fas fa-bed"></i></div>
            <div class="stat-details">
                <span class="stat-value"><?= (int)$totalRooms ?></span>
                <span class="stat-label">Chambres actives</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon bg-success-light"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-details">
                <span class="stat-value"><?= (int)$totalBookings ?></span>
                <span class="stat-label">Total réservations</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon bg-warning-light"><i class="fas fa-clock"></i></div>
            <div class="stat-details">
                <span class="stat-value"><?= (int)$pendingBookings ?></span>
                <span class="stat-label">En attente</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon bg-gold-light"><i class="fas fa-dollar-sign"></i></div>
            <div class="stat-details">
                <span class="stat-value">$<?= number_format($monthlyRevenue, 0) ?></span>
                <span class="stat-label">Revenus ce mois</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Bookings -->
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="card-header">
                <h5><i class="fas fa-list me-2"></i>Dernières réservations</h5>
                <a href="<?= SITE_URL ?>/dashboard/reservations/index.php" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Chambre</th>
                                <th>Dates</th>
                                <th>Statut</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentBookings)): ?>
                                <?php foreach ($recentBookings as $booking): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2"><?= strtoupper(substr($booking['first_name'], 0, 1)) ?></div>
                                            <div>
                                                <div class="fw-medium"><?= e($booking['first_name']) ?> <?= e($booking['last_name']) ?></div>
                                                <small class="text-muted"><?= e($booking['email']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= e($booking['room_name']) ?></td>
                                    <td><small><?= formatDate($booking['check_in'], 'd M') ?> - <?= formatDate($booking['check_out'], 'd M Y') ?></small></td>
                                    <td>
                                        <span class="badge bg-<?= $booking['status'] ?>">
                                            <?= statusLabel($booking['status']) ?>
                                        </span>
                                    </td>
                                    <td class="fw-medium">$<?= number_format(totalPrice($booking['check_in'], $booking['check_out'], $booking['price_per_night']), 0) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    Aucune réservation pour le moment
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Rooms & Quick Actions -->
    <div class="col-lg-4">
        <div class="dashboard-card">
            <div class="card-header">
                <h5><i class="fas fa-star me-2"></i>Chambres populaires</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($roomsStats)): ?>
                    <?php foreach ($roomsStats as $r): ?>
                    <div class="room-stat-item">
                        <div class="room-info">
                            <span class="room-name"><?= e($r['name']) ?></span>
                            <span class="room-bookings"><?= (int)$r['booking_count'] ?> réservation<?= $r['booking_count'] > 1 ? 's' : '' ?></span>
                        </div>
                        <div class="room-bar">
                            <div class="room-bar-fill" style="width: <?= $totalBookings > 0 ? round(($r['booking_count'] / $totalBookings) * 100) : 0 ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                <p class="text-muted text-center py-4">
                    <i class="fas fa-bed fa-2x mb-2 d-block"></i>Aucune donnée disponible
                </p>
                <?php endif; ?>
            </div>
        </div>

        <div class="dashboard-card mt-4">
            <div class="card-header">
                <h5><i class="fas fa-bolt me-2"></i>Actions rapides</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= SITE_URL ?>/dashboard/rooms/form.php" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-2"></i>Ajouter une chambre
                    </a>
                    <a href="<?= SITE_URL ?>/dashboard/reservations/index.php?status=pending" class="btn btn-outline-warning">
                        <i class="fas fa-clock me-2"></i>Voir en attente (<?= (int)$pendingBookings ?>)
                    </a>
                    <a href="<?= SITE_URL ?>/dashboard/settings/index.php" class="btn btn-outline-secondary">
                        <i class="fas fa-cog me-2"></i>Paramètres du site
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/dash_footer.php'; ?>
