<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/avatar_helpers.php';
require_admin_login();
$activeAdminNav = 'profile';
$pageTitle = 'BULIG | My Profile';

$adminPk = (int) $_SESSION['admin_pk'];
$avatarFile = null;
try {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('SELECT avatar_file FROM admins WHERE id = :id');
    $stmt->execute(['id' => $adminPk]);
    $avatarFile = $stmt->fetchColumn() ?: null;
} catch (Throwable $e) {
    // Fall back to the initial-letter avatar below.
}

$initial = strtoupper(substr((string) $_SESSION['full_name'], 0, 1)) ?: 'A';

$avatarSuccess = isset($_GET['avatar']);
$avatarError = $_GET['avatar_error'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include __DIR__ . '/../partials/head_dash.php'; ?>
<link rel="stylesheet" href="../css/manage.css">
</head>
<body class="bg-admin">
<div class="app-shell">
    <?php include __DIR__ . '/../partials/admin_sidebar.php'; ?>
    <div class="content-wrap">
        <?php include __DIR__ . '/../partials/mobile_topbar.php'; ?>
        <main class="dash-main">
            <h2 class="section-title">👤 My Profile</h2>

            <?php if ($avatarSuccess): ?><div class="form-success">✅ Profile photo updated!</div><?php endif; ?>
            <?php if ($avatarError): ?><p class="form-alert" role="alert"><?= htmlspecialchars($avatarError, ENT_QUOTES) ?></p><?php endif; ?>

            <div class="profile-card">
                <div class="profile-photo-row">
                    <?= bulig_avatar_html($avatarFile, 'a-admin', $initial, '../', 'avatar-lg') ?>
                    <form action="upload_avatar.php" method="post" enctype="multipart/form-data" class="avatar-upload-form">
                        <label class="avatar-upload-btn" for="avatarInput">📁 Choose Photo</label>
                        <input type="file" id="avatarInput" name="avatar" accept="image/png,image/jpeg,image/webp"
                               style="display:none;"
                               onchange="document.getElementById('avatarFileName').textContent = this.files[0] ? this.files[0].name : 'No file chosen';">
                        <span id="avatarFileName" class="avatar-filename">No file chosen</span>
                        <button type="submit" class="avatar-upload-submit">⬆️ Upload</button>
                    </form>
                </div>

                <div class="profile-row"><span>Full Name</span><span><?= htmlspecialchars($_SESSION['full_name'], ENT_QUOTES) ?></span></div>
                <div class="profile-row"><span>Admin ID</span><span><?= htmlspecialchars($_SESSION['admin_id'], ENT_QUOTES) ?></span></div>
                <div class="profile-row"><span>Role</span><span>System Administrator</span></div>
            </div>
        </main>
    </div>
</div>
<script src="../js/sidebar.js"></script>
</body>
</html>
