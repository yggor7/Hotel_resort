<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$db = getDB();
$contact = $db->query("SELECT * FROM contact_info LIMIT 1")->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone    = sanitize($_POST['phone'] ?? '');
    $email    = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $address  = sanitize($_POST['address'] ?? '');
    $facebook = sanitize($_POST['facebook'] ?? '');
    $instagram= sanitize($_POST['instagram'] ?? '');
    $twitter  = sanitize($_POST['twitter'] ?? '');
    $youtube  = sanitize($_POST['youtube'] ?? '');
    $maps     = $_POST['google_maps_embed'] ?? '';

    if ($contact) {
        $db->prepare("UPDATE contact_info SET phone=?,email=?,address=?,facebook=?,instagram=?,twitter=?,youtube=?,google_maps_embed=? WHERE id=?")->execute([$phone,$email,$address,$facebook,$instagram,$twitter,$youtube,$maps,$contact['id']]);
    } else {
        $db->prepare("INSERT INTO contact_info (phone,email,address,facebook,instagram,twitter,youtube,google_maps_embed) VALUES (?,?,?,?,?,?,?,?)")->execute([$phone,$email,$address,$facebook,$instagram,$twitter,$youtube,$maps]);
    }
    setFlash('success', 'Informations de contact enregistrées.');
    redirect(SITE_URL . '/dashboard/settings/contact.php');
}

$v = $contact ?: [];
$pageTitle = "Informations de contact";
include __DIR__ . '/../includes/dash_header.php';
?>
<div class="page-header"><div><h1>Informations de contact</h1><p>Coordonnées affichées sur le site</p></div></div>
<div class="dashboard-card"><div class="card-body">
    <form method="post">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Téléphone</label><input type="text" name="phone" class="form-control" value="<?= e($v['phone'] ?? '') ?>"></div>
            <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= e($v['email'] ?? '') ?>"></div>
            <div class="col-12"><label class="form-label">Adresse</label><textarea name="address" class="form-control" rows="2"><?= e($v['address'] ?? '') ?></textarea></div>
            <div class="col-md-6"><label class="form-label">Facebook URL</label><input type="url" name="facebook" class="form-control" value="<?= e($v['facebook'] ?? '') ?>"></div>
            <div class="col-md-6"><label class="form-label">Instagram URL</label><input type="url" name="instagram" class="form-control" value="<?= e($v['instagram'] ?? '') ?>"></div>
            <div class="col-md-6"><label class="form-label">Twitter URL</label><input type="url" name="twitter" class="form-control" value="<?= e($v['twitter'] ?? '') ?>"></div>
            <div class="col-md-6"><label class="form-label">YouTube URL</label><input type="url" name="youtube" class="form-control" value="<?= e($v['youtube'] ?? '') ?>"></div>
            <div class="col-12"><label class="form-label">Code Google Maps (iframe)</label><textarea name="google_maps_embed" class="form-control" rows="4" placeholder="&lt;iframe src=&quot;...&quot;&gt;&lt;/iframe&gt;"><?= e($v['google_maps_embed'] ?? '') ?></textarea><small class="text-muted">Copiez le code iframe depuis Google Maps > Partager > Intégrer une carte</small></div>
            <div class="col-12"><button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save me-2"></i>Enregistrer</button></div>
        </div>
    </form>
</div></div>
<?php include __DIR__ . '/../includes/dash_footer.php'; ?>
