<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();
$db = getDB();
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $row = $db->prepare("SELECT image FROM services WHERE id = ?");
    $row->execute([$id]);
    $s = $row->fetch();
    if ($s && $s['image']) deleteImage($s['image']);
    $db->prepare("DELETE FROM services WHERE id = ?")->execute([$id]);
    setFlash('success', 'Service supprimé.');
}
redirect(SITE_URL . '/dashboard/services/index.php');
