<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/level1_helpers.php';
require_teacher_login();

$activeTeacherNav = 'activities';
$pageTitle = 'BULIG | Activities';

$teacherPk = (int) $_SESSION['teacher_pk'];
$feed = [];
$dbError = false;
try {
    $pdo  = get_db_connection();
    $feed = bulig_teacher_recent_activity($pdo, $teacherPk, 60);
} catch (Throwable $e) {
    $dbError = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include __DIR__ . '/../partials/head_dash.php'; ?>
<link rel="stylesheet" href="../css/manage.css">
</head>
<body class="bg-teacher">
<div class="app-shell">
    <?php include __DIR__ . '/../partials/teacher_sidebar.php'; ?>
    <div class="content-wrap">
        <?php include __DIR__ . '/../partials/mobile_topbar.php'; ?>
        <main class="dash-main">
            <h2 class="section-title">📝 Activities</h2>
            <p style="color:var(--ink-soft); font-weight:700; margin-top:-8px;">Every lesson, unit, and assessment your pupils have completed, newest first.</p>

            <?php if ($dbError): ?>
                <div class="coming-soon">
                    <span class="cs-icon">⚠️</span>
                    <h1>Couldn't load activity</h1>
                    <p>There was a problem connecting to the database. Please check the connection settings and try again.</p>
                </div>
            <?php elseif (empty($feed)): ?>
                <div class="coming-soon">
                    <span class="cs-icon">📝</span>
                    <h1>No activity yet</h1>
                    <p>This feed fills up automatically as your pupils complete lessons, units, and assessments across every level.</p>
                </div>
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
        </main>
    </div>
</div>
<script src="../js/sidebar.js"></script>
</body>
</html>
