<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(SITE_URL . '/');
}

$email = trim($_POST['email'] ?? '');
$referer = $_SERVER['HTTP_REFERER'] ?? SITE_URL . '/';

// Validate email
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setFlash('error', 'Please enter a valid email address.');
    redirect($referer);
}

// Store in database if table exists, otherwise just confirm
try {
    $db = getDB();

    // Check if newsletter_subscribers table exists
    $tables = $db->query("SHOW TABLES LIKE 'newsletter_subscribers'")->fetchAll();

    if (!empty($tables)) {
        // Check if already subscribed
        $existing = $db->prepare("SELECT id FROM newsletter_subscribers WHERE email = ?");
        $existing->execute([$email]);

        if ($existing->fetch()) {
            setFlash('info', 'You are already subscribed to our newsletter.');
        } else {
            $stmt = $db->prepare("INSERT INTO newsletter_subscribers (email, subscribed_at) VALUES (?, NOW())");
            $stmt->execute([$email]);
            setFlash('success', 'Thank you! You have successfully subscribed to our newsletter.');
        }
    } else {
        // Table doesn't exist yet — just confirm to the user
        setFlash('success', 'Thank you for subscribing! We will keep you updated.');
    }
} catch (Exception $e) {
    setFlash('success', 'Thank you for subscribing! We will keep you updated.');
}

redirect($referer);
