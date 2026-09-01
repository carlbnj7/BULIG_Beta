<?php
/**
 * BULIG — Admin login.
 * Deliberately not linked from anywhere obvious — reached only via the
 * small dot on the main login page. Self-contained form + handler, mirroring
 * the pupil/teacher login pattern.
 */
declare(strict_types=1);
session_start();
require_once __DIR__ . '/config/database.php';

if (!empty($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin') {
    header('Location: admin/dashboard.php');
    exit;
}

$errorMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminId  = trim($_POST['admin_id'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    if ($adminId === '' || $password === '') {
        $errorMessage = 'Please fill in both fields.';
    } else {
        try {
            $pdo = get_db_connection();
            $stmt = $pdo->prepare('SELECT id, admin_id, password_hash, first_name, last_name, avatar_file FROM admins WHERE admin_id = :aid LIMIT 1');
            $stmt->execute(['aid' => $adminId]);
            $admin = $stmt->fetch();

            if (!$admin || !password_verify($password, $admin['password_hash'])) {
                $errorMessage = "That ID and password don't match our records.";
            } else {
                session_regenerate_id(true);
                $_SESSION['user_type'] = 'admin';
                $_SESSION['admin_pk']  = $admin['id'];
                $_SESSION['admin_id']  = $admin['admin_id'];
                $_SESSION['full_name'] = trim($admin['first_name'] . ' ' . $admin['last_name']);
                $_SESSION['avatar_file'] = $admin['avatar_file'];
                header('Location: admin/dashboard.php');
                exit;
            }
        } catch (Throwable $e) {
            $errorMessage = 'Something went wrong on our end. Please try again in a moment.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BULIG | Admin Sign In</title>
<link rel="icon" href="assets/bulig-logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700;800&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/manage.css">
</head>
<body>
<div class="scene">
    <main class="auth-card" id="authCard">
        <div class="brand">
            <img src="assets/bulig-logo.png" alt="BULIG" class="brand-logo">
            <p class="brand-tagline">Division of Bukidnon &middot; Reading Intervention Gateway</p>
        </div>
        <div class="torch-divider" aria-hidden="true"><span class="torch-flame"></span></div>

        <h1 class="reg-title">🛡️ Admin Sign In</h1>

        <?php if ($errorMessage): ?>
            <p class="form-alert" role="alert"><?= htmlspecialchars($errorMessage, ENT_QUOTES) ?></p>
        <?php endif; ?>

        <form action="admin_login.php" method="post" class="login-form" novalidate>
            <label class="field">
                <span class="field-label">Admin ID</span>
                <span class="field-control">
                    <span class="field-icon" aria-hidden="true">🆔</span>
                    <input type="text" name="admin_id" placeholder="e.g. A2026001" autocomplete="username" required>
                </span>
            </label>
            <label class="field">
                <span class="field-label">Password</span>
                <span class="field-control">
                    <span class="field-icon" aria-hidden="true">🔒</span>
                    <input type="password" name="password" placeholder="Enter your password" autocomplete="current-password" required class="js-password">
                    <button type="button" class="toggle-pw" aria-label="Show password">👁️</button>
                </span>
            </label>
            <button type="submit" class="btn-submit btn-teacher">
                <span class="btn-label">Sign In</span>
                <span class="btn-spinner" aria-hidden="true"></span>
            </button>
        </form>

        <p class="auth-subtext"><a href="index.php">← Back to pupil/teacher sign in</a></p>
    </main>
</div>
<script src="js/login.js"></script>
</body>
</html>
