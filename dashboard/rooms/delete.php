<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$db = getDB();
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $room = $db->prepare("SELECT image FROM rooms WHERE id = ?")->execute([$id]) ? $db->query("SELECT image FROM rooms WHERE id = $id")->fetch() : null;
    if ($room && $room['image']) deleteImage($room['image']);
    $db->prepare("DELETE FROM rooms WHERE id = ?")->execute([$id]);
    setFlash('success', 'Chambre supprimée avec succès.');
}
redirect(SITE_URL . '/dashboard/rooms/index.php');
