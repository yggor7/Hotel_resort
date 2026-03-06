<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$isEdit = isset($_GET['id']);
$db = getDB();
$item = null; $errors = [];

if ($isEdit) {
    $s = $db->prepare("SELECT * FROM gallery_images WHERE id = ?"); $s->execute([(int)$_GET['id']]); $item = $s->fetch();
    if (!$item) redirect(SITE_URL . '/dashboard/gallery/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = sanitize($_POST['title'] ?? '');
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    $orderPos = (int)($_POST['order_position'] ?? 0);

    if (empty($title)) $errors[] = 'Le titre est requis.';

    $imagePath = $item['image'] ?? '';
    if (!empty($_FILES['image']['name'])) {
        $res = uploadImage($_FILES['image'], 'uploads/gallery/');
        if (isset($res['error'])) $errors[] = $res['error'];
        else { if ($imagePath) deleteImage($imagePath); $imagePath = $res['path']; }
    } elseif (!$isEdit) {
        $errors[] = 'L\'image est requise.';
    }

    if (empty($errors)) {
        if ($isEdit) {
            $db->prepare("UPDATE gallery_images SET title=?,image=?,is_active=?,order_position=? WHERE id=?")->execute([$title,$imagePath,$isActive,$orderPos,$item['id']]);
            setFlash('success', 'Image modifiée.');
        } else {
            $db->prepare("INSERT INTO gallery_images (title,image,is_active,order_position) VALUES (?,?,?,?)")->execute([$title,$imagePath,$isActive,$orderPos]);
        }
        redirect(SITE_URL . '/dashboard/gallery/index.php');
    }
}
$v = $item ?: $_POST;
$pageTitle = $isEdit ? "Modifier l'image" : "Ajouter une image";
include __DIR__ . '/../includes/dash_header.php';
?>
<div class="page-header">
    <div><h1><?= $pageTitle ?></h1></div>
    <a href="<?= SITE_URL ?>/dashboard/gallery/index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Retour</a>
</div>
<?php if (!empty($errors)): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<div class="dashboard-card"><div class="card-body">
    <form method="post" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Titre *</label><input type="text" name="title" class="form-control" value="<?= e($v['title'] ?? '') ?>" required></div>
            <div class="col-md-3"><label class="form-label">Ordre</label><input type="number" name="order_position" class="form-control" value="<?= (int)($v['order_position'] ?? 0) ?>"></div>
            <div class="col-md-3 d-flex align-items-end"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" id="isActive" <?= ($v['is_active'] ?? 1) ? 'checked' : '' ?>><label class="form-check-label" for="isActive">Actif</label></div></div>
            <div class="col-md-6"><label class="form-label">Image <?= $isEdit ? '' : '*' ?></label><?php if ($isEdit && $item['image']): ?><div class="mb-2"><img src="<?= imageUrl($item['image']) ?>" style="height:100px;border-radius:6px;" alt=""></div><?php endif; ?><input type="file" name="image" class="form-control" accept="image/*" <?= !$isEdit ? 'required' : '' ?>></div>
            <div class="col-12"><button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save me-2"></i>Enregistrer</button> <a href="<?= SITE_URL ?>/dashboard/gallery/index.php" class="btn btn-outline-secondary btn-lg">Annuler</a></div>
        </div>
    </form>
</div></div>
<?php include __DIR__ . '/../includes/dash_footer.php'; ?>
