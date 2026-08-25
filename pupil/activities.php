<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_pupil_login();
$activePupilNav = 'activities';
$pageTitle = 'BULIG | Activities';
$csIcon = '✏️';
$csTitle = 'Your activities will show up here';
$csMessage = "Once you start Level 1, each lesson's mini-activities (mission cards, describing words, poems, and more) will appear on this page so you can jump back into any of them.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include __DIR__ . '/../partials/head_dash.php'; ?>
</head>
<body class="bg-pupil">
<div class="deco-layer" aria-hidden="true">
    <span class="deco d-star" style="top:14%; left:10%;">✦</span>
    <span class="deco d-book" style="bottom:10%; right:8%;">📖</span>
</div>
<div class="app-shell">
    <?php include __DIR__ . '/../partials/pupil_sidebar.php'; ?>
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
