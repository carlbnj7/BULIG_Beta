<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_teacher_login();

$activeTeacherNav = 'dashboard';
$pageTitle = 'BULIG | Teacher Dashboard';

$firstName = trim((string) explode(' ', (string) $_SESSION['full_name'])[0]) ?: 'Teacher';

// Real count from the database — everything else stays an honest zero-state
// until section-assignment and progress-tracking tables exist.
$totalPupils = 0;
try {
    $pdo = get_db_connection();
    $totalPupils = (int) $pdo->query('SELECT COUNT(*) FROM pupils')->fetchColumn();
} catch (Throwable $e) {
    // Leave totalPupils at 0 and let the page render normally.
}

$avgProgress    = 0;
$lessonsToGrade = 0;
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

            <section class="home-hero hero-teacher">
                <div class="hero-text">
                    <h1>Welcome back, <?= htmlspecialchars($firstName, ENT_QUOTES) ?></h1>
                    <p>Here's how your class is doing today.</p>
                </div>
                <div class="hero-emoji">🎓</div>
            </section>

            <h2 class="section-title">Class Overview</h2>
            <div class="stat-grid">
                <div class="stat-card">
                    <span class="stat-icon">👥</span>
                    <div class="stat-value"><?= $totalPupils ?></div>
                    <div class="stat-label">Registered Pupils</div>
                    <div class="stat-note"><?= $totalPupils === 0 ? 'No pupils registered yet' : 'System-wide total' ?></div>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">📈</span>
                    <div class="stat-value"><?= $avgProgress ?>%</div>
                    <div class="stat-label">Avg. Level 1 Progress</div>
                    <div class="stat-note"><?= $avgProgress === 0 ? 'Appears once pupils start Level 1' : 'Across the whole class' ?></div>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">📝</span>
                    <div class="stat-value"><?= $lessonsToGrade ?></div>
                    <div class="stat-label">Awaiting Review</div>
                    <div class="stat-note"><?= $lessonsToGrade === 0 ? 'Nothing needs your attention right now' : 'Need a rubric score' ?></div>
                </div>
            </div>

            <h2 class="section-title">Quick Actions</h2>
            <div class="action-grid">
                <a href="pupils.php" class="action-card is-primary acc-teacher">
                    <span class="action-icon">👩‍🏫</span>
                    <h3>My Pupils</h3>
                    <p>View the registered pupil roster and account details.</p>
                    <span class="action-go">Open Roster →</span>
                </a>
                <div class="action-card is-soon">
                    <span class="action-icon">📊</span>
                    <h3>Progress Reports</h3>
                    <p>Rubric scores and completion rates across all 12 lessons.</p>
                    <span class="pill-soon">Coming Soon</span>
                </div>
                <div class="action-card is-soon">
                    <span class="action-icon">📋</span>
                    <h3>Assignments</h3>
                    <p>Assign lessons and activities to specific sections.</p>
                    <span class="pill-soon">Coming Soon</span>
                </div>
            </div>

        </main>
    </div>
</div>
<script src="../js/sidebar.js"></script>
</body>
</html>
