<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/level1_helpers.php';
require_pupil_login();

$activePupilNav = 'home';
$pageTitle = 'BULIG | My Reading Journey';

$firstName = trim((string) explode(' ', (string) $_SESSION['full_name'])[0]) ?: 'Reader';

// Real progress, pulled from `pupil_progress` (Level 1 quest data).
try {
    $pdo     = get_db_connection();
    $summary = bulig_level1_summary($pdo, (int) $_SESSION['pupil_id']);
} catch (Throwable $e) {
    $summary = ['xp' => 0, 'completed' => [], 'streakDays' => 0];
}
$xp          = $summary['xp'];
$xpTarget    = 100;
$xpPercent   = min(100, (int) round(($xp / $xpTarget) * 100));
$badgesCount = count($summary['completed']); // one badge per completed Level 1 quest
$streakDays  = $summary['streakDays'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include __DIR__ . '/../partials/head_dash.php'; ?>
</head>
<body class="bg-pupil">

<div class="deco-layer" aria-hidden="true">
    <span class="deco d-star" style="top:10%; left:8%;">✦</span>
    <span class="deco d-cloud" style="top:6%; right:12%;">☁️</span>
    <span class="deco d-book" style="bottom:14%; left:6%;">📖</span>
    <span class="deco d-letter" style="top:46%; right:5%;">Bb</span>
    <span class="deco d-star" style="bottom:8%; right:20%; animation-delay:2s;">✦</span>
</div>

<div class="app-shell">
    <?php include __DIR__ . '/../partials/pupil_sidebar.php'; ?>

    <div class="content-wrap">
        <?php include __DIR__ . '/../partials/mobile_topbar.php'; ?>

        <main class="dash-main">

            <section class="home-hero hero-pupil">
                <div class="hero-text">
                    <h1>Hi, <?= htmlspecialchars($firstName, ENT_QUOTES) ?>! 👋</h1>
                    <p>Ready for today's reading quest?</p>
                </div>
                <div class="hero-emoji">📖</div>
            </section>

            <h2 class="section-title">⭐ Your Progress</h2>
            <div class="stat-grid">
                <div class="stat-card">
                    <span class="stat-icon">⭐</span>
                    <div class="stat-value"><?= $xp ?> XP</div>
                    <div class="stat-label">Experience</div>
                    <div class="xp-bar-track"><div class="xp-bar-fill" data-width="<?= $xpPercent ?>"></div></div>
                    <div class="stat-note"><?= $xp === 0 ? 'Finish an activity to earn your first XP!' : ($xp >= $xpTarget ? "You're on a roll!" : ($xpTarget - $xp) . ' XP to next level') ?></div>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">🏅</span>
                    <div class="stat-value"><?= $badgesCount ?></div>
                    <div class="stat-label">Badges Earned</div>
                    <div class="stat-note"><?= $badgesCount === 0 ? 'No badges yet — your first is waiting!' : 'Great work!' ?></div>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">🔥</span>
                    <div class="stat-value"><?= $streakDays ?></div>
                    <div class="stat-label">Day Streak</div>
                    <div class="stat-note"><?= $streakDays === 0 ? 'Start today to begin your streak!' : 'Keep it going!' ?></div>
                </div>
            </div>

            <h2 class="section-title">🚀 Quests</h2>
            <div class="action-grid">
                <a href="lessons.php" class="action-card is-primary acc-pupil">
                    <span class="action-icon">🚀</span>
                    <h3>Level 1: Oral Language</h3>
                    <p>12 fun activities — talk about yourself, follow directions, describe pictures, and more!</p>
                    <span class="action-go">Start Quest →</span>
                </a>
                <div class="action-card is-soon">
                    <span class="action-icon">🔒</span>
                    <h3>Level 2</h3>
                    <p>Unlocks after you complete Level 1.</p>
                    <span class="pill-soon">Coming Soon</span>
                </div>
                <a href="achievements.php" class="action-card">
                    <span class="action-icon">🎖️</span>
                    <h3>My Badges</h3>
                    <p>See every badge you've collected along the way.</p>
                    <span class="action-go" style="color:var(--bulig-green-dark);">View Badges →</span>
                </a>
            </div>

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
