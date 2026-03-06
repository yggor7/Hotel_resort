<?php
$pageTitle = "Section Hero";
include __DIR__ . '/../includes/dash_header.php';
$db = getDB();
$items = $db->query("SELECT * FROM hero_sections ORDER BY order_position ASC")->fetchAll();
?>
<div class="page-header">
    <div><h1>Section Hero</h1><p>Gérez les sections d'en-tête de votre site</p></div>
    <a href="<?= SITE_URL ?>/dashboard/hero/form.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Ajouter</a>
</div>
<div class="dashboard-card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead><tr><th>Image</th><th>Titre</th><th>Sous-titre</th><th>Statut</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($items as $h): ?>
            <tr>
                <td><?php if ($h['background_image'] && file_exists(ROOT_PATH . '/' . $h['background_image'])): ?><img src="<?= imageUrl($h['background_image']) ?>" style="width:60px;height:45px;object-fit:cover;border-radius:6px;" alt=""><?php else: ?><div class="img-placeholder"><i class="fas fa-image"></i></div><?php endif; ?></td>
                <td><div class="fw-medium"><?= e($h['title']) ?></div></td>
                <td><small class="text-muted"><?= truncateWords($h['subtitle'], 10) ?></small></td>
                <td><span class="badge <?= $h['is_active'] ? 'bg-confirmed' : 'bg-cancelled' ?>"><?= $h['is_active'] ? 'Actif' : 'Inactif' ?></span></td>
                <td><div class="d-flex gap-1"><a href="<?= SITE_URL ?>/dashboard/hero/form.php?id=<?= $h['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a><a href="<?= SITE_URL ?>/dashboard/hero/delete.php?id=<?= $h['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer?')"><i class="fas fa-trash"></i></a></div></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($items)): ?><tr><td colspan="5" class="text-center py-5 text-muted"><i class="fas fa-star fa-3x mb-3 d-block"></i>Aucune section hero</td></tr><?php endif; ?>
        </tbody>
    </table>
</div></div></div>
<?php include __DIR__ . '/../includes/dash_footer.php'; ?>
