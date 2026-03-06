<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Déjà connecté
if (isAdminLoggedIn()) {
    redirect(SITE_URL . '/dashboard/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = ? AND is_active = 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_id']       = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            setFlash('success', 'Bienvenue, ' . $user['username'] . '!');
            redirect(SITE_URL . '/dashboard/index.php');
        } else {
            $error = 'Identifiants incorrects. Vérifiez votre nom d\'utilisateur et mot de passe.';
        }
    } else {
        $error = 'Veuillez remplir tous les champs.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - CozyStay Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/dashboard.css">
    <style>
        body { background: linear-gradient(135deg, #3d4a3e 0%, #1a1a1a 100%); min-height: 100vh; display: flex; align-items: center; }
        .login-card { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 25px 60px rgba(0,0,0,0.3); max-width: 420px; width: 100%; }
        .login-header { background: linear-gradient(135deg, #3d4a3e, #2d3a2e); padding: 40px 30px; text-align: center; }
        .login-logo { font-size: 32px; font-weight: 700; color: white; letter-spacing: 4px; }
        .login-logo-stars { color: #c9a96e; font-size: 8px; letter-spacing: 3px; display: block; margin-top: 5px; }
        .login-body { padding: 40px 30px; }
        .btn-login { background: #3d4a3e; border: none; color: white; padding: 14px; font-size: 15px; border-radius: 10px; }
        .btn-login:hover { background: #2d3a2e; color: white; }
        .form-control { border-radius: 10px; padding: 12px 16px; border: 2px solid #eee; }
        .form-control:focus { border-color: #3d4a3e; box-shadow: 0 0 0 3px rgba(61,74,62,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-card mx-auto">
                    <div class="login-header">
                        <div class="login-logo">COZYSTAY</div>
                        <span class="login-logo-stars">
                            <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i>
                        </span>
                        <p class="text-white-50 mt-3 mb-0 small">Panneau d'administration</p>
                    </div>
                    <div class="login-body">
                        <h4 class="mb-1 fw-bold">Connexion</h4>
                        <p class="text-muted small mb-4">Entrez vos identifiants pour accéder au tableau de bord</p>

                        <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?= e($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Nom d'utilisateur</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0 bg-white"><i class="fas fa-user text-muted"></i></span>
                                    <input type="text" name="username" class="form-control border-start-0" placeholder="admin" value="<?= e($_POST['username'] ?? '') ?>" required autofocus>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-medium">Mot de passe</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0 bg-white"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0" placeholder="••••••••" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-login w-100">
                                <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="<?= SITE_URL ?>/" class="text-muted small">
                                <i class="fas fa-arrow-left me-1"></i>Retour au site
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
