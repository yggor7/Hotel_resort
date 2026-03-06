<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$db = getDB();
$settings = $db->query("SELECT * FROM site_settings LIMIT 1")->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $siteName    = sanitize($_POST['site_name'] ?? 'CozyStay');
    $tagline     = sanitize($_POST['tagline'] ?? '');
    $checkIn     = sanitize($_POST['check_in_time'] ?? '15:00');
    $checkOut    = sanitize($_POST['check_out_time'] ?? '11:00');
    $minAge      = (int)($_POST['min_age'] ?? 18);
    $currency    = sanitize($_POST['currency'] ?? '$');

    $logoPath = $settings['site_logo'] ?? '';
    if (!empty($_FILES['site_logo']['name'])) {
        $res = uploadImage($_FILES['site_logo'], 'uploads/');
        if (!isset($res['error'])) { if ($logoPath) deleteImage($logoPath); $logoPath = $res['path']; }
    }

    if ($settings) {
        $db->prepare("UPDATE site_settings SET site_name=?,tagline=?,check_in_time=?,check_out_time=?,min_age=?,currency=?,site_logo=? WHERE id=?")->execute([$siteName,$tagline,$checkIn,$checkOut,$minAge,$currency,$logoPath,$settings['id']]);
    } else {
        $db->prepare("INSERT INTO site_settings (site_name,tagline,check_in_time,check_out_time,min_age,currency,site_logo) VALUES (?,?,?,?,?,?,?)")->execute([$siteName,$tagline,$checkIn,$checkOut,$minAge,$currency,$logoPath]);
    }
    setFlash('success', 'Paramètres enregistrés avec succès.');
    redirect(SITE_URL . '/dashboard/settings/index.php');
}

$v = $settings ?: [];
$pageTitle = "Paramètres du site";
include __DIR__ . '/../includes/dash_header.php';
?>
<div class="page-header"><div><h1>Paramètres du site</h1><p>Configurez les paramètres généraux</p></div></div>
<div class="dashboard-card"><div class="card-body">
    <form method="post" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Nom du site</label><input type="text" name="site_name" class="form-control" value="<?= e($v['site_name'] ?? 'CozyStay') ?>"></div>
            <div class="col-md-6"><label class="form-label">Slogan</label><input type="text" name="tagline" class="form-control" value="<?= e($v['tagline'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label">Heure check-in</label><input type="time" name="check_in_time" class="form-control" value="<?= e($v['check_in_time'] ?? '15:00') ?>"></div>
            <div class="col-md-3"><label class="form-label">Heure check-out</label><input type="time" name="check_out_time" class="form-control" value="<?= e($v['check_out_time'] ?? '11:00') ?>"></div>
            <div class="col-md-3"><label class="form-label">Âge minimum</label><input type="number" name="min_age" class="form-control" value="<?= (int)($v['min_age'] ?? 18) ?>" min="0"></div>
            <div class="col-md-3"><label class="form-label">Devise</label><input type="text" name="currency" class="form-control" value="<?= e($v['currency'] ?? '$') ?>"></div>
            <div class="col-md-6"><label class="form-label">Logo du site</label><?php if (!empty($v['site_logo'])): ?><div class="mb-2"><img src="<?= imageUrl($v['site_logo']) ?>" style="height:60px;" alt=""></div><?php endif; ?><input type="file" name="site_logo" class="form-control" accept="image/*"></div>
            <div class="col-12"><button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save me-2"></i>Enregistrer</button></div>
        </div>
    </form>
</div></div>
<?php include __DIR__ . '/../includes/dash_footer.php'; ?>
