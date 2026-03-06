<?php
$pageTitle = "Services";
include __DIR__ . '/../includes/dash_header.php';
$db = getDB();
$services = $db->query("SELECT * FROM services ORDER BY order_position ASC")->fetchAll();
?>
<div class="page-header">
    <div><h1>Services</h1><p>Gérez les services de votre resort</p></div>
    <a href="<?= SITE_URL ?>/dashboard/services/form.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Ajouter un service</a>
</div>
<div class="dashboard-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Image</th><th>Titre</th><th>Statut</th><th>Ordre</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach ($services as $s): ?>
                    <tr>
                        <td><?php if ($s['image'] && file_exists(ROOT_PATH . '/' . $s['image'])): ?><img src="<?= imageUrl($s['image']) ?>" style="width:60px;height:45px;object-fit:cover;border-radius:6px;" alt=""><?php else: ?><div class="img-placeholder"><i class="fas fa-image"></i></div><?php endif; ?></td>
                        <td><div class="fw-medium"><?= e($s['title']) ?></div><small class="text-muted"><?= e($s['slug']) ?></small></td>
                        <td><span class="badge <?= $s['is_active'] ? 'bg-confirmed' : 'bg-cancelled' ?>"><?= $s['is_active'] ? 'Actif' : 'Inactif' ?></span></td>
                        <td><?= (int)$s['order_position'] ?></td>
                        <td><div class="d-flex gap-1"><a href="<?= SITE_URL ?>/dashboard/services/form.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a><a href="<?= SITE_URL ?>/dashboard/services/delete.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer?')"><i class="fas fa-trash"></i></a></div></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($services)): ?><tr><td colspan="5" class="text-center py-5 text-muted"><i class="fas fa-concierge-bell fa-3x mb-3 d-block"></i>Aucun service</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/dash_footer.php'; ?>
