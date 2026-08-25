<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_teacher_login();
$activeTeacherNav = 'materials';
$pageTitle = 'BULIG | Learning Materials';
$csIcon = '📚';
$csTitle = 'Learning materials are on the way';
$csMessage = "Upload and organize reading toolkits, mission cards, and picture sets for your class here once this feature is built.";
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
