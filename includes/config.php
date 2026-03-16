<?php
// ============================================
// Configuration de la base de données
// Modifier ces valeurs pour votre cPanel
// ============================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'digitalweb_hotel');
define('DB_USER', 'digitalweb_hoteluser');
define('DB_PASS', '@hoteluser202Digital*');
define('DB_CHARSET', 'utf8mb4');

// URL du site (sans slash final)
define('SITE_URL', 'https://hotel.digitalweb.africa');

// Chemin absolu vers la racine du projet
define('ROOT_PATH', dirname(__DIR__));

// Chemin vers les uploads
define('UPLOAD_PATH', ROOT_PATH . '/uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');

// Chemin vers les assets
define('ASSETS_URL', SITE_URL . '/assets/');

// Session name
define('SESSION_NAME', 'island_hotel_admin');

// Timezone
date_default_timezone_set('Africa/Bujumbura');

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}

// ============================================
// Connexion PDO
// ============================================
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die('<div style="color:red;padding:20px;font-family:sans-serif;"><strong>Erreur de connexion à la base de données:</strong><br>' . htmlspecialchars($e->getMessage()) . '<br><br>Vérifiez les paramètres dans <code>includes/config.php</code></div>');
        }
    }
    return $pdo;
}
