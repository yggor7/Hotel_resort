<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$isEdit = isset($_GET['id']);
$db = getDB();
$item = null; $errors = [];

if ($isEdit) {
    $s = $db->prepare("SELECT * FROM testimonials WHERE id = ?"); $s->execute([(int)$_GET['id']]); $item = $s->fetch();
    if (!$item) redirect(SITE_URL . '/dashboard/testimonials/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author   = sanitize($_POST['author'] ?? '');
    $source   = sanitize($_POST['source'] ?? 'TripAdvisor');
    $content  = sanitize($_POST['content'] ?? '');
    $rating   = max(1, min(5, (int)($_POST['rating'] ?? 5)));
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    $orderPos = (int)($_POST['order_position'] ?? 0);

    if (empty($author))  $errors[] = 'L\'auteur est requis.';
    if (empty($content)) $errors[] = 'Le contenu est requis.';

    $imagePath = $item['image'] ?? '';
    if (!empty($_FILES['image']['name'])) {
        $res = uploadImage($_FILES['image'], 'uploads/testimonials/');
        if (isset($res['error'])) $errors[] = $res['error'];
        else { if ($imagePath) deleteImage($imagePath); $imagePath = $res['path']; }
    }

    if (empty($errors)) {
        if ($isEdit) {
            $db->prepare("UPDATE testimonials SET author=?,source=?,content=?,rating=?,image=?,is_active=?,order_position=? WHERE id=?")->execute([$author,$source,$content,$rating,$imagePath,$isActive,$orderPos,$item['id']]);
            setFlash('success', 'Témoignage modifié.');
        } else {
            $db->prepare("INSERT INTO testimonials (author,source,content,rating,image,is_active,order_position) VALUES (?,?,?,?,?,?,?)")->execute([$author,$source,$content,$rating,$imagePath,$isActive,$orderPos]);
        }
        redirect(SITE_URL . '/dashboard/testimonials/index.php');
    }
}
$v = $item ?: $_POST;
$pageTitle = $isEdit ? "Modifier le témoignage" : "Ajouter un témoignage";
include __DIR__ . '/../includes/dash_header.php';
?>
<div class="page-header">
    <div><h1><?= $pageTitle ?></h1></div>
    <a href="<?= SITE_URL ?>/dashboard/testimonials/index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Retour</a>
</div>
<?php if (!empty($errors)): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<div class="dashboard-card"><div class="card-body">
    <form method="post" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Auteur *</label><input type="text" name="author" class="form-control" value="<?= e($v['author'] ?? '') ?>" required></div>
            <div class="col-md-4"><label class="form-label">Source</label><input type="text" name="source" class="form-control" value="<?= e($v['source'] ?? 'TripAdvisor') ?>"></div>
            <div class="col-md-2"><label class="form-label">Note (1-5)</label><input type="number" name="rating" class="form-control" value="<?= (int)($v['rating'] ?? 5) ?>" min="1" max="5"></div>
            <div class="col-12"><label class="form-label">Contenu *</label><textarea name="content" class="form-control" rows="4" required><?= e($v['content'] ?? '') ?></textarea></div>
            <div class="col-md-4"><label class="form-label">Ordre</label><input type="number" name="order_position" class="form-control" value="<?= (int)($v['order_position'] ?? 0) ?>"></div>
            <div class="col-md-4 d-flex align-items-end"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" id="isActive" <?= ($v['is_active'] ?? 1) ? 'checked' : '' ?>><label class="form-check-label" for="isActive">Actif</label></div></div>
            <div class="col-md-6"><label class="form-label">Photo (optionnel)</label><?php if ($isEdit && $item['image']): ?><div class="mb-2"><img src="<?= imageUrl($item['image']) ?>" style="height:60px;border-radius:50%;" alt=""></div><?php endif; ?><input type="file" name="image" class="form-control" accept="image/*"></div>
            <div class="col-12"><button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save me-2"></i>Enregistrer</button> <a href="<?= SITE_URL ?>/dashboard/testimonials/index.php" class="btn btn-outline-secondary btn-lg">Annuler</a></div>
        </div>
    </form>
</div></div>
<?php include __DIR__ . '/../includes/dash_footer.php'; ?>
