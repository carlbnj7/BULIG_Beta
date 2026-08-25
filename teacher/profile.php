<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_teacher_login();
$activeTeacherNav = 'profile';
$pageTitle = 'BULIG | My Profile';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include __DIR__ . '/../partials/head_dash.php'; ?>
</head>
<body class="bg-teacher">
<div class="app-shell">
    <?php include __DIR__ . '/../partials/teacher_sidebar.php'; ?>
    <div class="content-wrap">
        <?php include __DIR__ . '/../partials/mobile_topbar.php'; ?>
        <main class="dash-main">
            <h2 class="section-title">👤 My Profile</h2>
            <div class="profile-card">
                <div class="profile-row"><span>Full Name</span><span><?= htmlspecialchars($_SESSION['full_name'], ENT_QUOTES) ?></span></div>
                <div class="profile-row"><span>Teacher ID</span><span><?= htmlspecialchars($_SESSION['teacher_id'], ENT_QUOTES) ?></span></div>
                <div class="profile-row"><span>Role</span><span>Teacher</span></div>
            </div>
        </main>
    </div>
</div>
<script src="../js/sidebar.js"></script>
</body>
</html>
