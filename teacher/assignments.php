<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_teacher_login();
$activeTeacherNav = 'assignments';
$pageTitle = 'BULIG | Assignments';
$csIcon = '📋';
$csTitle = 'Assignments are on the way';
$csMessage = "Assign specific lessons or activities to your section and track who still needs to complete them.";
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
            <?php include __DIR__ . '/../partials/coming_soon.php'; ?>
        </main>
    </div>
</div>
<script src="../js/sidebar.js"></script>
</body>
</html>
