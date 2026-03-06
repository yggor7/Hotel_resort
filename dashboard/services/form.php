<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$isEdit = isset($_GET['id']);
$db = getDB();
$service = null;
$errors = [];

if ($isEdit) {
    $stmt = $db->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([(int)$_GET['id']]);
    $service = $stmt->fetch();
    if (!$service) redirect(SITE_URL . '/dashboard/services/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = sanitize($_POST['title'] ?? '');
    $slug        = slugify($_POST['slug'] ?: ($_POST['title'] ?? ''));
    $description = $_POST['description'] ?? '';
    $isActive    = isset($_POST['is_active']) ? 1 : 0;
    $orderPos    = (int)($_POST['order_position'] ?? 0);

    if (empty($title)) $errors[] = 'Le titre est requis.';

    $imagePath = $service['image'] ?? '';
    if (!empty($_FILES['image']['name'])) {
        $uploadResult = uploadImage($_FILES['image'], 'uploads/services/');
        if (isset($uploadResult['error'])) $errors[] = $uploadResult['error'];
        else { if ($imagePath) deleteImage($imagePath); $imagePath = $uploadResult['path']; }
    }

    if (empty($errors)) {
        if ($isEdit) {
            $db->prepare("UPDATE services SET title=?, slug=?, description=?, image=?, is_active=?, order_position=? WHERE id=?")->execute([$title, $slug, $description, $imagePath, $isActive, $orderPos, $service['id']]);
            setFlash('success', 'Service modifié avec succès.');
        } else {
            $db->prepare("INSERT INTO services (title, slug, description, image, is_active, order_position) VALUES (?,?,?,?,?,?)")->execute([$title, $slug, $description, $imagePath, $isActive, $orderPos]);
        }
        redirect(SITE_URL . '/dashboard/services/index.php');
    }
}
$v = $service ?: $_POST;
$pageTitle = $isEdit ? "Modifier le service" : "Ajouter un service";
include __DIR__ . '/../includes/dash_header.php';
?>
<div class="page-header">
    <div><nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= SITE_URL ?>/dashboard/services/index.php">Services</a></li><li class="breadcrumb-item active"><?= $isEdit ? 'Modifier' : 'Ajouter' ?></li></ol></nav><h1><?= $pageTitle ?></h1></div>
    <a href="<?= SITE_URL ?>/dashboard/services/index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Retour</a>
</div>
<?php if (!empty($errors)): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<div class="dashboard-card"><div class="card-body">
    <form method="post" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-8"><label class="form-label">Titre *</label><input type="text" name="title" class="form-control" value="<?= e($v['title'] ?? '') ?>" required></div>
            <div class="col-md-4"><label class="form-label">Slug</label><input type="text" name="slug" class="form-control" value="<?= e($v['slug'] ?? '') ?>"></div>
            <div class="col-12"><label class="form-label">Description *</label><textarea name="description" id="editor" class="form-control" rows="6"><?= e($v['description'] ?? '') ?></textarea></div>
            <div class="col-md-4"><label class="form-label">Ordre</label><input type="number" name="order_position" class="form-control" value="<?= (int)($v['order_position'] ?? 0) ?>"></div>
            <div class="col-md-4 d-flex align-items-end"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" id="isActive" <?= ($v['is_active'] ?? 1) ? 'checked' : '' ?>><label class="form-check-label" for="isActive">Actif</label></div></div>
            <div class="col-md-6"><label class="form-label">Image</label><?php if ($isEdit && $service['image']): ?><div class="mb-2"><img src="<?= imageUrl($service['image']) ?>" style="height:80px;border-radius:6px;" alt=""></div><?php endif; ?><input type="file" name="image" class="form-control" accept="image/*"></div>
            <div class="col-12"><button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save me-2"></i>Enregistrer</button> <a href="<?= SITE_URL ?>/dashboard/services/index.php" class="btn btn-outline-secondary btn-lg">Annuler</a></div>
        </div>
    </form>
</div></div>
<?php
$extraJs = '<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script><script>ClassicEditor.create(document.querySelector("#editor")).catch(error => console.error(error));</script>';
include __DIR__ . '/../includes/dash_footer.php'; ?>
