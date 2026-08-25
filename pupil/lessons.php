<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_pupil_login();
$activePupilNav = 'lessons';
$pageTitle = 'BULIG | My Lessons';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include __DIR__ . '/../partials/head_dash.php'; ?>
</head>
<body class="bg-pupil">
<div class="deco-layer" aria-hidden="true">
    <span class="deco d-star" style="top:12%; right:10%;">✦</span>
    <span class="deco d-cloud" style="bottom:12%; left:8%;">☁️</span>
</div>
<div class="app-shell">
    <?php include __DIR__ . '/../partials/pupil_sidebar.php'; ?>
    <div class="content-wrap">
        <?php include __DIR__ . '/../partials/mobile_topbar.php'; ?>
        <main class="dash-main">
            <h2 class="section-title">📚 My Lessons</h2>
            <div class="action-grid">
                <a href="level1.php" class="action-card is-primary acc-pupil">
                    <span class="action-icon">🚀</span>
                    <h3>Level 1: Oral Language</h3>
                    <p>12 lessons — talking about yourself, following directions, describing words, poems, and tongue twisters.</p>
                    <span class="action-go">Enter Level 1 →</span>
                </a>
                <?php for ($i = 2; $i <= 4; $i++): ?>
                <div class="action-card is-soon">
                    <span class="action-icon">🔒</span>
                    <h3>Level <?= $i ?></h3>
                    <p>Unlocks after finishing the level before it.</p>
                    <span class="pill-soon">Locked</span>
                </div>
                <?php endfor; ?>
            </div>
        </main>
    </div>
</div>
<script src="../js/sidebar.js"></script>
</body>
</html>
