<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDB();
$slug = $_GET['slug'] ?? '';

if (!$slug) {
    redirect(SITE_URL . '/rooms.php');
}

$stmt = $db->prepare("SELECT * FROM rooms WHERE slug = ? AND is_active = 1");
$stmt->execute([$slug]);
$room = $stmt->fetch();

if (!$room) {
    redirect(SITE_URL . '/rooms.php');
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = sanitize($_POST['first_name'] ?? '');
    $lastName  = sanitize($_POST['last_name'] ?? '');
    $email     = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone     = sanitize($_POST['phone'] ?? '');
    $checkIn   = sanitize($_POST['check_in'] ?? '');
    $checkOut  = sanitize($_POST['check_out'] ?? '');
    $guests    = (int)($_POST['guests'] ?? 1);
    $message   = sanitize($_POST['message'] ?? '');

    // Validation
    if (empty($firstName)) $errors[] = 'Le prénom est requis.';
    if (empty($lastName))  $errors[] = 'Le nom est requis.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalide.';
    if (empty($phone))     $errors[] = 'Le téléphone est requis.';
    if (empty($checkIn))   $errors[] = 'La date d\'arrivée est requise.';
    if (empty($checkOut))  $errors[] = 'La date de départ est requise.';
    if ($checkIn >= $checkOut) $errors[] = 'La date de départ doit être après la date d\'arrivée.';
    if ($guests < 1 || $guests > $room['guests']) $errors[] = 'Nombre d\'invités invalide (max: ' . $room['guests'] . ').';

    if (empty($errors)) {
        try {
            $stmtInsert = $db->prepare("INSERT INTO bookings (room_id, first_name, last_name, email, phone, check_in, check_out, guests, message, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
            $stmtInsert->execute([$room['id'], $firstName, $lastName, $email, $phone, $checkIn, $checkOut, $guests, $message]);
            setFlash('success', 'Votre réservation a été enregistrée avec succès! Nous vous contacterons bientôt.');
            redirect(SITE_URL . '/room.php?slug=' . $room['slug']);
        } catch (Exception $ex) {
            $errors[] = 'Une erreur est survenue. Veuillez réessayer.';
        }
    }
}

$contactInfo = getContactInfo();
$pageTitle = "Réserver " . $room['name'];
include __DIR__ . '/includes/header.php';
?>

<section class="py-5" style="margin-top: 100px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Réservation - <?= e($room['name']) ?></h3>
                    </div>
                    <div class="card-body p-4">

                        <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $err): ?>
                                <li><?= e($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <!-- Room Summary -->
                        <div class="room-summary mb-4 p-3 bg-light rounded">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <img src="<?= imageUrl($room['image']) ?>" alt="<?= e($room['name']) ?>" class="img-fluid rounded">
                                </div>
                                <div class="col-md-8">
                                    <h5 class="mb-2"><?= e($room['name']) ?></h5>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-expand-arrows-alt me-2"></i><?= (int)$room['size'] ?> m² |
                                        <i class="fas fa-user ms-2 me-2"></i><?= (int)$room['guests'] ?> invités |
                                        <i class="fas fa-bed ms-2 me-2"></i><?= (int)$room['beds'] ?> <?= e($room['bed_type']) ?>
                                    </p>
                                    <p class="h5 text-primary mb-0">$<?= number_format($room['price_per_night'], 2) ?> / nuit</p>
                                </div>
                            </div>
                        </div>

                        <form method="post" class="booking-form">

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Prénom *</label>
                                    <input type="text" name="first_name" class="form-control" value="<?= e($_POST['first_name'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nom *</label>
                                    <input type="text" name="last_name" class="form-control" value="<?= e($_POST['last_name'] ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="email" class="form-control" value="<?= e($_POST['email'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Téléphone *</label>
                                    <input type="tel" name="phone" class="form-control" value="<?= e($_POST['phone'] ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Date d'arrivée *</label>
                                    <input type="text" name="check_in" class="form-control datepicker" value="<?= e($_POST['check_in'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Date de départ *</label>
                                    <input type="text" name="check_out" class="form-control datepicker" value="<?= e($_POST['check_out'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Nombre d'invités *</label>
                                    <select name="guests" class="form-select">
                                        <?php for ($i = 1; $i <= $room['guests']; $i++): ?>
                                        <option value="<?= $i ?>" <?= (isset($_POST['guests']) && $_POST['guests'] == $i) ? 'selected' : '' ?>><?= $i ?> invité<?= $i > 1 ? 's' : '' ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Message (optionnel)</label>
                                <textarea name="message" class="form-control" rows="4"><?= e($_POST['message'] ?? '') ?></textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-check me-2"></i>Confirmer la réservation
                                </button>
                                <a href="<?= SITE_URL ?>/room.php?slug=<?= e($room['slug']) ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Retour
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$extraJs = '<script>
    flatpickr(".datepicker", {
        dateFormat: "Y-m-d",
        minDate: "today",
        disableMobile: true
    });
</script>';
include __DIR__ . '/includes/footer.php';
?>
