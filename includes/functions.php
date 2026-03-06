<?php
require_once __DIR__ . '/config.php';

// ============================================
// Fonctions utilitaires
// ============================================

/**
 * Générer un slug à partir d'un texte
 */
function slugify($text) {
    $text = strtolower($text);
    $text = preg_replace('/[àáâãäå]/u', 'a', $text);
    $text = preg_replace('/[èéêë]/u', 'e', $text);
    $text = preg_replace('/[ìíîï]/u', 'i', $text);
    $text = preg_replace('/[òóôõö]/u', 'o', $text);
    $text = preg_replace('/[ùúûü]/u', 'u', $text);
    $text = preg_replace('/[ç]/u', 'c', $text);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

/**
 * Tronquer un texte (strip HTML)
 */
function truncateWords($text, $words = 20) {
    $text = strip_tags($text);
    $wordArray = explode(' ', $text);
    if (count($wordArray) > $words) {
        return implode(' ', array_slice($wordArray, 0, $words)) . '...';
    }
    return $text;
}

/**
 * Sécuriser l'affichage HTML
 */
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect
 */
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

/**
 * Ajouter un message flash
 */
function setFlash($type, $message) {
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

/**
 * Récupérer et effacer les messages flash
 */
function getFlash() {
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

/**
 * Uploader une image
 */
function uploadImage($file, $dir = 'uploads/') {
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        return ['error' => 'Format non autorisé. Utilisez: jpg, jpeg, png, gif, webp'];
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        return ['error' => 'Fichier trop grand (max 5MB)'];
    }

    $uploadDir = ROOT_PATH . '/' . $dir;
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $filename = uniqid() . '_' . time() . '.' . $ext;
    $destination = $uploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => true, 'path' => $dir . $filename];
    }

    return ['error' => 'Erreur lors de l\'upload'];
}

/**
 * Supprimer une image
 */
function deleteImage($path) {
    if ($path && file_exists(ROOT_PATH . '/' . $path)) {
        unlink(ROOT_PATH . '/' . $path);
    }
}

/**
 * Récupérer les paramètres du site
 */
function getSiteSettings() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM site_settings LIMIT 1");
    return $stmt->fetch() ?: [
        'site_name' => 'CozyStay',
        'currency' => '$',
        'check_in_time' => '15:00',
        'check_out_time' => '11:00',
    ];
}

/**
 * Récupérer les informations de contact
 */
function getContactInfo() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM contact_info LIMIT 1");
    return $stmt->fetch() ?: null;
}

/**
 * Vérifier si l'admin est connecté
 */
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Exiger la connexion admin
 */
function requireAdmin() {
    if (!isAdminLoggedIn()) {
        redirect('/dashboard/login.php');
    }
}

/**
 * Calculer le nombre de nuits
 */
function totalNights($checkIn, $checkOut) {
    $d1 = new DateTime($checkIn);
    $d2 = new DateTime($checkOut);
    return $d2->diff($d1)->days;
}

/**
 * Calculer le prix total
 */
function totalPrice($checkIn, $checkOut, $pricePerNight) {
    return totalNights($checkIn, $checkOut) * $pricePerNight;
}

/**
 * Formater une date en français
 */
function formatDate($date, $format = 'd M Y') {
    $d = new DateTime($date);
    return $d->format($format);
}

/**
 * Afficher les étoiles de notation
 */
function renderStars($rating, $max = 5) {
    $html = '';
    for ($i = 1; $i <= $max; $i++) {
        if ($i <= $rating) {
            $html .= '<i class="fas fa-star text-warning"></i>';
        } else {
            $html .= '<i class="far fa-star text-warning"></i>';
        }
    }
    return $html;
}

/**
 * Récupérer l'URL d'une image ou retourner une image par défaut
 */
function imageUrl($path, $default = null) {
    if ($path && file_exists(ROOT_PATH . '/' . $path)) {
        return SITE_URL . '/' . $path;
    }
    if ($default) {
        return SITE_URL . '/assets/images/' . $default;
    }
    return SITE_URL . '/assets/images/sara-dubler-Koei_7yYtIo-unsplash.jpg';
}

/**
 * Sanitize input
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Obtenir le libellé du statut
 */
function statusLabel($status) {
    $labels = [
        'pending'   => 'En attente',
        'confirmed' => 'Confirmé',
        'cancelled' => 'Annulé',
    ];
    return $labels[$status] ?? $status;
}
