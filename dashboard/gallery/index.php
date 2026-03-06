<?php
$pageTitle = "Galerie";
include __DIR__ . '/../includes/dash_header.php';
$db = getDB();
$items = $db->query("SELECT * FROM gallery_images ORDER BY order_position ASC")->fetchAll();
?>
<div class="page-header">
    <div><h1>Galerie</h1><p>Gérez les images de la galerie d'accueil</p></div>
    <a href="<?= SITE_URL ?>/dashboard/gallery/form.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Ajouter une image</a>
</div>
<div class="row g-4">
    <?php if (!empty($items)): ?>
        <?php foreach ($items as $img): ?>
        <div class="col-md-4 col-lg-3">
            <div class="gallery-item">
                <div class="gallery-image" style="position:relative;aspect-ratio:4/3;overflow:hidden;">
                    <img src="<?= imageUrl($img['image']) ?>" style="width:100%;height:100%;object-fit:cover;" alt="<?= e($img['title']) ?>">
                    <div class="gallery-overlay">
                        <a href="<?= SITE_URL ?>/dashboard/gallery/form.php?id=<?= $img['id'] ?>" class="btn btn-sm btn-light"><i class="fas fa-edit"></i></a>
                        <a href="<?= SITE_URL ?>/dashboard/gallery/delete.php?id=<?= $img['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer?')"><i class="fas fa-trash"></i></a>
                    </div>
                </div>
                <div class="gallery-info p-2">
                    <div class="fw-medium small"><?= e($img['title']) ?></div>
                    <span class="badge <?= $img['is_active'] ? 'bg-confirmed' : 'bg-cancelled' ?>"><?= $img['is_active'] ? 'Actif' : 'Inactif' ?></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
    <div class="col-12 text-center py-5 text-muted"><i class="fas fa-images fa-3x mb-3 d-block"></i>Aucune image dans la galerie</div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../includes/dash_footer.php'; ?>
