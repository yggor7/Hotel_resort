<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$isEdit = isset($_GET['id']);
$db = getDB();
$room = null;
$errors = [];

if ($isEdit) {
    $stmt = $db->prepare("SELECT * FROM rooms WHERE id = ?");
    $stmt->execute([(int)$_GET['id']]);
    $room = $stmt->fetch();
    if (!$room) redirect(SITE_URL . '/dashboard/rooms/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name         = sanitize($_POST['name'] ?? '');
    $slug         = slugify($_POST['slug'] ?: ($_POST['name'] ?? ''));
    $description  = $_POST['description'] ?? '';
    $size         = (int)($_POST['size'] ?? 0);
    $guests       = (int)($_POST['guests'] ?? 1);
    $beds         = (int)($_POST['beds'] ?? 1);
    $bedType      = sanitize($_POST['bed_type'] ?? 'King Bed');
    $price        = (float)($_POST['price_per_night'] ?? 0);
    $isFeatured   = isset($_POST['is_featured']) ? 1 : 0;
    $isActive     = isset($_POST['is_active']) ? 1 : 0;
    $orderPos     = (int)($_POST['order_position'] ?? 0);

    if (empty($name)) $errors[] = 'Le nom est requis.';
    if ($size <= 0)   $errors[] = 'La superficie doit être positive.';
    if ($price <= 0)  $errors[] = 'Le prix doit être positif.';

    // Upload image
    $imagePath = $room['image'] ?? '';
    if (!empty($_FILES['image']['name'])) {
        $uploadResult = uploadImage($_FILES['image'], 'uploads/rooms/');
        if (isset($uploadResult['error'])) {
            $errors[] = $uploadResult['error'];
        } else {
            if ($imagePath) deleteImage($imagePath);
            $imagePath = $uploadResult['path'];
        }
    }

    if (empty($errors)) {
        if ($isEdit) {
            $stmt = $db->prepare("UPDATE rooms SET name=?, slug=?, description=?, size=?, guests=?, beds=?, bed_type=?, price_per_night=?, image=?, is_featured=?, is_active=?, order_position=? WHERE id=?");
            $stmt->execute([$name, $slug, $description, $size, $guests, $beds, $bedType, $price, $imagePath, $isFeatured, $isActive, $orderPos, $room['id']]);
            setFlash('success', 'Chambre modifiée avec succès.');
        } else {
            $stmt = $db->prepare("INSERT INTO rooms (name, slug, description, size, guests, beds, bed_type, price_per_night, image, is_featured, is_active, order_position) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$name, $slug, $description, $size, $guests, $beds, $bedType, $price, $imagePath, $isFeatured, $isActive, $orderPos]);
        }
        redirect(SITE_URL . '/dashboard/rooms/index.php');
    }
}

$v = $room ?: $_POST;
$pageTitle = $isEdit ? "Modifier la chambre" : "Ajouter une chambre";
include __DIR__ . '/../includes/dash_header.php';
?>

<div class="page-header">
    <div>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= SITE_URL ?>/dashboard/rooms/index.php">Chambres</a></li><li class="breadcrumb-item active"><?= $isEdit ? 'Modifier' : 'Ajouter' ?></li></ol></nav>
        <h1><?= $pageTitle ?></h1>
    </div>
    <a href="<?= SITE_URL ?>/dashboard/rooms/index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Retour</a>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="dashboard-card">
    <div class="card-body">
        <form method="post" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Nom *</label>
                    <input type="text" name="name" class="form-control" value="<?= e($v['name'] ?? '') ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Slug (auto-généré)</label>
                    <input type="text" name="slug" class="form-control" value="<?= e($v['slug'] ?? '') ?>" placeholder="Laissez vide pour auto-génération">
                </div>
                <div class="col-12">
                    <label class="form-label">Description *</label>
                    <textarea name="description" id="editor" class="form-control" rows="6"><?= e($v['description'] ?? '') ?></textarea>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Superficie (m²) *</label>
                    <input type="number" name="size" class="form-control" value="<?= (int)($v['size'] ?? 0) ?>" min="1" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Invités max *</label>
                    <input type="number" name="guests" class="form-control" value="<?= (int)($v['guests'] ?? 1) ?>" min="1" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nombre de lits *</label>
                    <input type="number" name="beds" class="form-control" value="<?= (int)($v['beds'] ?? 1) ?>" min="1" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Type de lit</label>
                    <select name="bed_type" class="form-select">
                        <?php foreach (['King Bed', 'Queen Bed', 'Twin Bed', 'Double Bed', 'Single Bed'] as $bt): ?>
                        <option value="<?= $bt ?>" <?= ($v['bed_type'] ?? 'King Bed') === $bt ? 'selected' : '' ?>><?= $bt ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Prix par nuit ($) *</label>
                    <input type="number" name="price_per_night" class="form-control" value="<?= (float)($v['price_per_night'] ?? 0) ?>" step="0.01" min="0" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ordre d'affichage</label>
                    <input type="number" name="order_position" class="form-control" value="<?= (int)($v['order_position'] ?? 0) ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-check me-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" <?= ($v['is_active'] ?? 1) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="isActive">Actif</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured" <?= ($v['is_featured'] ?? 0) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="isFeatured">En vedette</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Image principale</label>
                    <?php if ($isEdit && $room['image']): ?>
                    <div class="mb-2">
                        <img src="<?= imageUrl($room['image']) ?>" style="height:80px;border-radius:6px;" alt="">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i><?= $isEdit ? 'Enregistrer les modifications' : 'Créer la chambre' ?>
                    </button>
                    <a href="<?= SITE_URL ?>/dashboard/rooms/index.php" class="btn btn-outline-secondary btn-lg ms-2">Annuler</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
$extraJs = '
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
ClassicEditor.create(document.querySelector("#editor")).catch(error => console.error(error));
</script>';
include __DIR__ . '/../includes/dash_footer.php'; ?>
