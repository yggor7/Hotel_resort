<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();
$db = getDB();
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $s = $db->prepare("SELECT background_image FROM hero_sections WHERE id = ?"); $s->execute([$id]); $r = $s->fetch();
    if ($r && $r['background_image']) deleteImage($r['background_image']);
    $db->prepare("DELETE FROM hero_sections WHERE id = ?")->execute([$id]);
    setFlash('success', 'Section Hero supprimée.');
}
redirect(SITE_URL . '/dashboard/hero/index.php');
