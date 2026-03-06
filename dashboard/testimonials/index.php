<?php
$pageTitle = "Témoignages";
include __DIR__ . '/../includes/dash_header.php';
$db = getDB();
$items = $db->query("SELECT * FROM testimonials ORDER BY order_position ASC")->fetchAll();
?>
<div class="page-header">
    <div><h1>Témoignages</h1><p>Gérez les avis de vos clients</p></div>
    <a href="<?= SITE_URL ?>/dashboard/testimonials/form.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Ajouter</a>
</div>
<div class="dashboard-card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead><tr><th>Auteur</th><th>Source</th><th>Note</th><th>Statut</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($items as $t): ?>
            <tr>
                <td><div class="fw-medium"><?= e($t['author']) ?></div><small class="text-muted"><?= truncateWords($t['content'], 10) ?></small></td>
                <td><?= e($t['source']) ?></td>
                <td><?= renderStars($t['rating']) ?></td>
                <td><span class="badge <?= $t['is_active'] ? 'bg-confirmed' : 'bg-cancelled' ?>"><?= $t['is_active'] ? 'Actif' : 'Inactif' ?></span></td>
                <td><div class="d-flex gap-1"><a href="<?= SITE_URL ?>/dashboard/testimonials/form.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a><a href="<?= SITE_URL ?>/dashboard/testimonials/delete.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer?')"><i class="fas fa-trash"></i></a></div></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($items)): ?><tr><td colspan="5" class="text-center py-5 text-muted"><i class="fas fa-quote-left fa-3x mb-3 d-block"></i>Aucun témoignage</td></tr><?php endif; ?>
        </tbody>
    </table>
</div></div></div>
<?php include __DIR__ . '/../includes/dash_footer.php'; ?>
