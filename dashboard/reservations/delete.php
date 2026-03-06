<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$db = getDB();
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $db->prepare("DELETE FROM bookings WHERE id = ?")->execute([$id]);
    setFlash('success', 'Réservation supprimée avec succès.');
}
redirect(SITE_URL . '/dashboard/reservations/index.php');
