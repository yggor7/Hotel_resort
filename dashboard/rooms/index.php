<?php
$pageTitle = "Chambres";
include __DIR__ . '/../includes/dash_header.php';

$db = getDB();
$rooms = $db->query("SELECT * FROM rooms ORDER BY order_position ASC")->fetchAll();
?>

<div class="page-header">
    <div><h1>Chambres</h1><p>Gérez vos chambres et suites</p></div>
    <a href="<?= SITE_URL ?>/dashboard/rooms/form.php" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Ajouter une chambre
    </a>
</div>

<div class="dashboard-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Prix/Nuit</th>
                        <th>Taille</th>
                        <th>Invités</th>
                        <th>Statut</th>
                        <th>Ordre</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rooms as $room): ?>
                    <tr>
                        <td>
                            <?php if ($room['image'] && file_exists(ROOT_PATH . '/' . $room['image'])): ?>
                            <img src="<?= imageUrl($room['image']) ?>" style="width:60px;height:45px;object-fit:cover;border-radius:6px;" alt="">
                            <?php else: ?>
                            <div class="img-placeholder"><i class="fas fa-image"></i></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-medium"><?= e($room['name']) ?></div>
                            <small class="text-muted"><?= e($room['slug']) ?></small>
                        </td>
                        <td>$<?= number_format($room['price_per_night'], 2) ?></td>
                        <td><?= (int)$room['size'] ?> m²</td>
                        <td><?= (int)$room['guests'] ?></td>
                        <td>
                            <span class="badge <?= $room['is_active'] ? 'bg-confirmed' : 'bg-cancelled' ?>">
                                <?= $room['is_active'] ? 'Actif' : 'Inactif' ?>
                            </span>
                        </td>
                        <td><?= (int)$room['order_position'] ?></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="<?= SITE_URL ?>/dashboard/rooms/form.php?id=<?= $room['id'] ?>" class="btn btn-sm btn-outline-primary" title="Modifier"><i class="fas fa-edit"></i></a>
                                <a href="<?= SITE_URL ?>/dashboard/rooms/delete.php?id=<?= $room['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer cette chambre?')" title="Supprimer"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($rooms)): ?>
                    <tr><td colspan="8" class="text-center py-5 text-muted"><i class="fas fa-bed fa-3x mb-3 d-block"></i>Aucune chambre</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/dash_footer.php'; ?>
