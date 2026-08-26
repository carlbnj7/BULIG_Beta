<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/level1_helpers.php';
require_pupil_login();
$activePupilNav = 'progress';
$pageTitle = 'BULIG | My Progress';

try {
    $pdo     = get_db_connection();
    $summary = bulig_level1_summary($pdo, (int) $_SESSION['pupil_id']);
} catch (Throwable $e) {
    $summary = ['xp' => 0, 'completed' => [], 'lessonsTotal' => 12];
}
$xp = $summary['xp']; $xpTarget = 100;
$xpPercent = min(100, (int) round(($xp / $xpTarget) * 100));
$lessonsDone = count($summary['completed']); $lessonsTotal = $summary['lessonsTotal'];
$lessonPercent = min(100, (int) round(($lessonsDone / $lessonsTotal) * 100));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include __DIR__ . '/../partials/head_dash.php'; ?>
</head>
<body class="bg-pupil">
<div class="deco-layer" aria-hidden="true">
    <span class="deco d-cloud" style="top:8%; left:14%;">☁️</span>
</div>
<div class="app-shell">
    <?php include __DIR__ . '/../partials/pupil_sidebar.php'; ?>
    <div class="content-wrap">
        <?php include __DIR__ . '/../partials/mobile_topbar.php'; ?>
        <main class="dash-main">
            <h2 class="section-title">📊 My Progress</h2>
            <div class="stat-grid" style="grid-template-columns: repeat(2, 1fr);">
                <div class="stat-card">
                    <span class="stat-icon">⭐</span>
                    <div class="stat-value"><?= $xp ?> XP</div>
                    <div class="stat-label">Total Level 1 Experience</div>
                    <div class="xp-bar-track"><div class="xp-bar-fill" data-width="<?= $xpPercent ?>"></div></div>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">📘</span>
                    <div class="stat-value"><?= $lessonsDone ?> / <?= $lessonsTotal ?></div>
                    <div class="stat-label">Level 1 Lessons Completed</div>
                    <div class="xp-bar-track"><div class="xp-bar-fill" data-width="<?= $lessonPercent ?>"></div></div>
                </div>
            </div>
            <p style="color:var(--ink-soft); font-weight:700;">Your progress bars fill up as you complete each Level 1 activity. Start your first lesson to begin!</p>
        </main>
    </div>
</div>
<script src="../js/sidebar.js"></script>
<script>
    document.querySelectorAll('.xp-bar-fill').forEach(function (bar) {
        requestAnimationFrame(function () { bar.style.width = bar.dataset.width + '%'; });
    });
</script>
</body>
</html>
