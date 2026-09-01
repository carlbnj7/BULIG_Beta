<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_admin_login();

$activeAdminNav = 'dashboard';
$pageTitle = 'BULIG | Admin Dashboard';
$firstName = trim((string) explode(' ', (string) $_SESSION['full_name'])[0]) ?: 'Admin';

$totalPupils = 0; $totalTeachers = 0; $totalActivities = 0;
try {
    $pdo = get_db_connection();
    $totalPupils   = (int) $pdo->query('SELECT COUNT(*) FROM pupils')->fetchColumn();
    $totalTeachers = (int) $pdo->query('SELECT COUNT(*) FROM teachers')->fetchColumn();
    $totalActivities = (int) $pdo->query('SELECT COUNT(*) FROM pupil_progress')->fetchColumn();
} catch (Throwable $e) {
    // Leave counts at 0 and render normally.
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include __DIR__ . '/../partials/head_dash.php'; ?>
<link rel="stylesheet" href="../css/manage.css">
</head>
<body class="bg-admin">
<div class="app-shell">
    <?php include __DIR__ . '/../partials/admin_sidebar.php'; ?>
    <div class="content-wrap">
        <?php include __DIR__ . '/../partials/mobile_topbar.php'; ?>
        <main class="dash-main">

            <section class="home-hero hero-admin">
                <div class="hero-text">
                    <h1>Welcome, <?= htmlspecialchars($firstName, ENT_QUOTES) ?></h1>
                    <p>System-wide overview across every teacher and pupil.</p>
                </div>
                <div class="hero-emoji">🛡️</div>
            </section>

            <h2 class="section-title">System Overview</h2>
            <div class="stat-grid">
                <div class="stat-card">
                    <span class="stat-icon">🍎</span>
                    <div class="stat-value"><?= $totalTeachers ?></div>
                    <div class="stat-label">Registered Teachers</div>
                    <div class="stat-note">Across the whole system</div>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">🧒</span>
                    <div class="stat-value"><?= $totalPupils ?></div>
                    <div class="stat-label">Registered Pupils</div>
                    <div class="stat-note">Across every teacher</div>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">🎯</span>
                    <div class="stat-value"><?= $totalActivities ?></div>
                    <div class="stat-label">Activities Completed</div>
                    <div class="stat-note">Across every level and pupil</div>
                </div>
            </div>

            <h2 class="section-title">Quick Links</h2>
            <div class="action-grid">
                <a href="teachers.php" class="action-card is-primary acc-admin">
                    <span class="action-icon">🍎</span>
                    <h3>Manage Teachers</h3>
                    <p>Add new teacher accounts and review every teacher in the system.</p>
                    <span class="action-go">Open →</span>
                </a>
                <a href="pupils.php" class="action-card">
                    <span class="action-icon">🧒</span>
                    <h3>Manage Pupils</h3>
                    <p>System-wide pupil roster — reassign teachers or remove accounts.</p>
                    <span class="action-go" style="color:var(--bulig-green-dark);">Open →</span>
                </a>
            </div>

        </main>
    </div>
</div>
<script src="../js/sidebar.js"></script>
</body>
</html>
