<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$isEdit = isset($_GET['id']);
$db = getDB();
$item = null; $errors = [];

if ($isEdit) {
    $s = $db->prepare("SELECT * FROM hero_sections WHERE id = ?"); $s->execute([(int)$_GET['id']]); $item = $s->fetch();
    if (!$item) redirect(SITE_URL . '/dashboard/hero/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = sanitize($_POST['title'] ?? '');
    $subtitle    = sanitize($_POST['subtitle'] ?? '');
    $description = $_POST['description'] ?? '';
    $btnText     = sanitize($_POST['button_text'] ?? 'Book Now');
    $btnLink     = sanitize($_POST['button_link'] ?? '/rooms.php');
    $isActive    = isset($_POST['is_active']) ? 1 : 0;
    $orderPos    = (int)($_POST['order_position'] ?? 0);

    if (empty($title)) $errors[] = 'Le titre est requis.';

    $imagePath = $item['background_image'] ?? '';
    if (!empty($_FILES['background_image']['name'])) {
        $res = uploadImage($_FILES['background_image'], 'uploads/hero/');
        if (isset($res['error'])) $errors[] = $res['error'];
        else { if ($imagePath) deleteImage($imagePath); $imagePath = $res['path']; }
    }

    if (empty($errors)) {
        if ($isEdit) {
            $db->prepare("UPDATE hero_sections SET title=?,subtitle=?,description=?,background_image=?,button_text=?,button_link=?,is_active=?,order_position=? WHERE id=?")->execute([$title,$subtitle,$description,$imagePath,$btnText,$btnLink,$isActive,$orderPos,$item['id']]);
            setFlash('success', 'Section Hero modifiée.');
        } else {
            $db->prepare("INSERT INTO hero_sections (title,subtitle,description,background_image,button_text,button_link,is_active,order_position) VALUES (?,?,?,?,?,?,?,?)")->execute([$title,$subtitle,$description,$imagePath,$btnText,$btnLink,$isActive,$orderPos]);
        }
        redirect(SITE_URL . '/dashboard/hero/index.php');
    }
}
$v = $item ?: $_POST;
$pageTitle = $isEdit ? "Modifier le Hero" : "Ajouter un Hero";
include __DIR__ . '/../includes/dash_header.php';
?>
<div class="page-header">
    <div><h1><?= $pageTitle ?></h1></div>
    <a href="<?= SITE_URL ?>/dashboard/hero/index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Retour</a>
</div>
<?php if (!empty($errors)): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<div class="dashboard-card"><div class="card-body">
    <form method="post" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-12"><label class="form-label">Titre *</label><input type="text" name="title" class="form-control" value="<?= e($v['title'] ?? '') ?>" required></div>
            <div class="col-12"><label class="form-label">Sous-titre</label><input type="text" name="subtitle" class="form-control" value="<?= e($v['subtitle'] ?? '') ?>"></div>
            <div class="col-12"><label class="form-label">Description</label><textarea name="description" id="editor" class="form-control" rows="4"><?= e($v['description'] ?? '') ?></textarea></div>
            <div class="col-md-4"><label class="form-label">Texte du bouton</label><input type="text" name="button_text" class="form-control" value="<?= e($v['button_text'] ?? 'Book Now') ?>"></div>
            <div class="col-md-4"><label class="form-label">Lien du bouton</label><input type="text" name="button_link" class="form-control" value="<?= e($v['button_link'] ?? '/rooms.php') ?>"></div>
            <div class="col-md-2"><label class="form-label">Ordre</label><input type="number" name="order_position" class="form-control" value="<?= (int)($v['order_position'] ?? 0) ?>"></div>
            <div class="col-md-2 d-flex align-items-end"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" id="isActive" <?= ($v['is_active'] ?? 1) ? 'checked' : '' ?>><label class="form-check-label" for="isActive">Actif</label></div></div>
            <div class="col-md-6"><label class="form-label">Image de fond</label><?php if ($isEdit && $item['background_image']): ?><div class="mb-2"><img src="<?= imageUrl($item['background_image']) ?>" style="height:80px;border-radius:6px;" alt=""></div><?php endif; ?><input type="file" name="background_image" class="form-control" accept="image/*"></div>
            <div class="col-12"><button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save me-2"></i>Enregistrer</button> <a href="<?= SITE_URL ?>/dashboard/hero/index.php" class="btn btn-outline-secondary btn-lg">Annuler</a></div>
        </div>
    </form>
</div></div>
<?php
$extraJs = '<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script><script>ClassicEditor.create(document.querySelector("#editor")).catch(error => console.error(error));</script>';
include __DIR__ . '/../includes/dash_footer.php'; ?>
