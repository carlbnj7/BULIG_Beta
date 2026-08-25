<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_teacher_login();
$activeTeacherNav = 'activities';
$pageTitle = 'BULIG | Activities';
$csIcon = '📝';
$csTitle = 'Activity tracking is on the way';
$csMessage = "Once pupils start completing Level 1 activities, you'll be able to review and score them here.";
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
