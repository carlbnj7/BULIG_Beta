<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_pupil_login();
$activePupilNav = 'lessons';
$pageTitle = 'BULIG | Level 1 - Oral Language';
$csIcon = '🛠️';
$csTitle = 'Level 1 is almost ready!';
$csMessage = "We're building your 12-lesson Oral Language quest — sentence starters, mission cards, describing words, poems, and tongue twisters, all with EXP and badges. Check back soon!";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include __DIR__ . '/../partials/head_dash.php'; ?>
</head>
<body class="bg-pupil">
<div class="deco-layer" aria-hidden="true">
    <span class="deco d-star" style="top:14%; left:12%;">✦</span>
    <span class="deco d-book" style="bottom:12%; right:10%;">📖</span>
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
