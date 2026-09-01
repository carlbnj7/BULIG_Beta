<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/level1_helpers.php';
require_once __DIR__ . '/../config/level2_helpers.php';
require_once __DIR__ . '/../config/level2b_helpers.php';
require_once __DIR__ . '/../config/level3_helpers.php';
require_once __DIR__ . '/../config/avatar_helpers.php';
require_teacher_login();

$activeTeacherNav = 'dashboard';
$pageTitle = 'BULIG | Teacher Dashboard';

$firstName = trim((string) explode(' ', (string) $_SESSION['full_name'])[0]) ?: 'Teacher';
$teacherPk = (int) $_SESSION['teacher_pk'];
$justRegistered = isset($_GET['welcome']);
$initial = strtoupper(substr((string) $_SESSION['full_name'], 0, 1)) ?: 'T';

// Real numbers, scoped to this teacher's roster (their pupils + any
// legacy/unassigned ones), pulled from pupil_progress.
$totalPupils = 0; $avgProgress = 0; $assessmentsSubmitted = 0; $recentActivity = [];
try {
    $pdo    = get_db_connection();
    $roster = bulig_level1_roster($pdo, $teacherPk);
    $totalPupils = count($roster);
    if ($totalPupils > 0) {
        $totalPct = 0;
        foreach ($roster as $p) {
            $totalPct += (count($p['completed']) / BULIG_L1_LESSON_COUNT) * 100;
            if ($p['preDone'])  { $assessmentsSubmitted++; }
            if ($p['postDone']) { $assessmentsSubmitted++; }
        }
        $avgProgress = (int) round($totalPct / $totalPupils);
    }
    $recentActivity = bulig_teacher_recent_activity($pdo, $teacherPk, 6);
} catch (Throwable $e) {
    // Leave counts at 0 and let the page render normally.
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

            <section class="home-hero hero-teacher">
                <div class="hero-text">
                    <h1>Welcome back, <?= htmlspecialchars($firstName, ENT_QUOTES) ?></h1>
                    <p><?= $justRegistered ? 'Your teacher account is ready — here\'s your class overview.' : "Here's how your class is doing today." ?></p>
                </div>
                <div class="hero-emoji">🎓</div>
            </section>

            <div class="who-you-are">
                <?= bulig_avatar_html($_SESSION['avatar_file'] ?? null, 'a-teacher', $initial, '../') ?>
                <div>
                    <div class="who-you-are-name"><?= htmlspecialchars($_SESSION['full_name'], ENT_QUOTES) ?></div>
                    <div class="who-you-are-sub">Teacher · <?= $totalPupils ?> pupil<?= $totalPupils === 1 ? '' : 's' ?> in your roster</div>
                </div>
            </div>

            <h2 class="section-title">Class Overview</h2>
            <div class="stat-grid">
                <div class="stat-card">
                    <span class="stat-icon">👥</span>
                    <div class="stat-value"><?= $totalPupils ?></div>
                    <div class="stat-label">My Pupils</div>
                    <div class="stat-note"><?= $totalPupils === 0 ? 'Add your first pupil to get started' : 'Owned + unassigned pupils' ?></div>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">📈</span>
                    <div class="stat-value"><?= $avgProgress ?>%</div>
                    <div class="stat-label">Avg. Level 1 Progress</div>
                    <div class="stat-note"><?= $avgProgress === 0 ? 'Appears once pupils start Level 1' : 'Across your roster' ?></div>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">📝</span>
                    <div class="stat-value"><?= $assessmentsSubmitted ?></div>
                    <div class="stat-label">Assessments Submitted</div>
                    <div class="stat-note"><?= $assessmentsSubmitted === 0 ? 'Pre/Post assessments will appear here' : 'Ready for you to review' ?></div>
                </div>
            </div>

            <h2 class="section-title">Quick Actions</h2>
            <div class="action-grid">
                <a href="pupils.php" class="action-card is-primary acc-teacher">
                    <span class="action-icon">👩‍🏫</span>
                    <h3>My Pupils</h3>
                    <p>Add, remove, and assign starting levels for your pupils.</p>
                    <span class="action-go">Open Roster →</span>
                </a>
                <a href="progress.php" class="action-card">
                    <span class="action-icon">📊</span>
                    <h3>Progress Reports</h3>
                    <p>XP, quest completion, and assessment answers per pupil.</p>
                    <span class="action-go" style="color:var(--bulig-green-dark);">View Progress →</span>
                </a>
                <a href="assignments.php" class="action-card">
                    <span class="action-icon">📋</span>
                    <h3>Assignments</h3>
                    <p>Assign lessons and activities to specific sections.</p>
                    <span class="action-go" style="color:var(--bulig-green-dark);">Open Assignments →</span>
                </a>
            </div>

            <h2 class="section-title">🕒 Recent Class Activity</h2>
            <?php if (empty($recentActivity)): ?>
                <div class="recent-activity-empty">Nothing yet — activity will appear here as your pupils complete lessons.</div>
            <?php else: ?>
                <ul class="recent-activity-list">
                    <?php foreach ($recentActivity as $a): ?>
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
