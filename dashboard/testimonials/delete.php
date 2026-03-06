<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();
$db = getDB();
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $s = $db->prepare("SELECT image FROM testimonials WHERE id = ?"); $s->execute([$id]); $r = $s->fetch();
    if ($r && $r['image']) deleteImage($r['image']);
    $db->prepare("DELETE FROM testimonials WHERE id = ?")->execute([$id]);
    setFlash('success', 'Témoignage supprimé.');
}
redirect(SITE_URL . '/dashboard/testimonials/index.php');
