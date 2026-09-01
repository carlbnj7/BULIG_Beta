<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/level1_helpers.php';
require_pupil_login();
$activePupilNav = 'activities';
$pageTitle = 'BULIG | My Activities';

$pupilId = (int) $_SESSION['pupil_id'];
$feed = [];
$totalCount = 0;
$totalXp = 0;
$dbError = false;
try {
    $pdo  = get_db_connection();
    $feed = bulig_pupil_recent_activity($pdo, $pupilId, 100);
    $totalCount = count($feed);
    foreach ($feed as $a) { $totalXp += $a['xp']; }
} catch (Throwable $e) {
    $dbError = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include __DIR__ . '/../partials/head_dash.php'; ?>
</head>
<body class="bg-pupil">
<div class="app-shell">
    <?php include __DIR__ . '/../partials/pupil_sidebar.php'; ?>
    <div class="content-wrap">
        <?php include __DIR__ . '/../partials/mobile_topbar.php'; ?>
        <main class="dash-main">
            <h2 class="section-title">📝 My Activities</h2>
            <p style="color:var(--ink-soft); font-weight:700; margin-top:-8px;">Every lesson you've finished, newest first!</p>

            <?php if ($dbError): ?>
                <div class="coming-soon">
                    <span class="cs-icon">⚠️</span>
                    <h1>Couldn't load your activities</h1>
                    <p>There was a problem connecting to the database. Please check the connection settings and try again.</p>
                </div>
            <?php else: ?>
                <div class="stat-grid" style="grid-template-columns: repeat(2, 1fr); margin-bottom: 18px;">
                    <div class="stat-card">
                        <span class="stat-icon">✅</span>
                        <div class="stat-value"><?= $totalCount ?></div>
                        <div class="stat-label">Activities Completed</div>
                    </div>
                    <div class="stat-card">
                        <span class="stat-icon">⭐</span>
                        <div class="stat-value"><?= $totalXp ?> XP</div>
                        <div class="stat-label">Earned From Activities</div>
                    </div>
                </div>

                <?php if (empty($feed)): ?>
                    <div class="recent-activity-empty">Nothing yet — finish your first activity in <a href="lessons.php">My Lessons</a> to see it here!</div>
                <?php else: ?>
                    <ul class="recent-activity-list">
                        <?php foreach ($feed as $a): ?>
                        <li>
                            <span class="recent-activity-icon"><?= $a['icon'] ?></span>
                            <span class="recent-activity-text"><?= htmlspecialchars($a['text'], ENT_QUOTES) ?> <?= $a['xp'] > 0 ? '(+' . $a['xp'] . ' XP)' : '' ?></span>
                            <span class="recent-activity-when"><?= htmlspecialchars($a['when'], ENT_QUOTES) ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            <?php endif; ?>
        </main>
    </div>
</div>
<script src="../js/sidebar.js"></script>
</body>
</html>
