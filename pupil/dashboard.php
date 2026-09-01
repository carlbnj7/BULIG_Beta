<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/level1_helpers.php';
require_once __DIR__ . '/../config/level2_helpers.php';
require_once __DIR__ . '/../config/level2b_helpers.php';
require_once __DIR__ . '/../config/level3_helpers.php';
require_once __DIR__ . '/../config/level4_helpers.php';
require_once __DIR__ . '/../config/avatar_helpers.php';
require_pupil_login();

$activePupilNav = 'home';
$pageTitle = 'BULIG | My Reading Journey';

$firstName = trim((string) explode(' ', (string) $_SESSION['full_name'])[0]) ?: 'Reader';

// Real progress, pulled from `pupil_progress` (Level 1 quest data).
try {
    $pdo            = get_db_connection();
    $pupilId        = (int) $_SESSION['pupil_id'];
    $summary        = bulig_level1_summary($pdo, $pupilId);
    $level1Complete = bulig_level1_is_complete($pdo, $pupilId);
    $summary2       = bulig_level2_summary($pdo, $pupilId);
    $level2Complete = bulig_level2_is_complete($pdo, $pupilId);
    $summary2b      = bulig_level2b_summary($pdo, $pupilId);
    $level2bComplete = bulig_level2b_is_complete($pdo, $pupilId);
    $summary3       = bulig_level3_summary($pdo, $pupilId);
    $level3Complete = bulig_level3_is_complete($pdo, $pupilId);
    $pupilGrade     = bulig_pupil_grade($pdo, $pupilId);
    $assignedLevel  = bulig_pupil_current_level($pdo, $pupilId);
    $summary4       = $pupilGrade ? bulig_level4_summary($pdo, $pupilId, $pupilGrade) : ['xp' => 0, 'completed' => [], 'lessonsTotal' => 0, 'postDone' => false];
    $recentActivity = bulig_pupil_recent_activity($pdo, $pupilId, 5);
} catch (Throwable $e) {
    $summary        = ['xp' => 0, 'completed' => [], 'streakDays' => 0];
    $level1Complete = false;
    $summary2       = ['xp' => 0, 'completed' => [], 'lessonsTotal' => BULIG_L2_LESSON_COUNT];
    $level2Complete = false;
    $summary2b      = ['xp' => 0, 'completed' => [], 'lessonsTotal' => BULIG_L2B_LESSON_COUNT];
    $level2bComplete = false;
    $summary3       = ['xp' => 0, 'completed' => [], 'lessonsTotal' => BULIG_L3_LESSON_COUNT];
    $level3Complete = false;
    $pupilGrade     = null;
    $assignedLevel  = 1;
    $summary4       = ['xp' => 0, 'completed' => [], 'lessonsTotal' => 0, 'postDone' => false];
    $recentActivity = [];
}
$level2Unlocked  = bulig_level_unlocked(2, $level1Complete, $assignedLevel);
$level2bUnlocked = bulig_level_unlocked(3, $level2Complete, $assignedLevel);
$level3Unlocked  = bulig_level_unlocked(4, $level2bComplete, $assignedLevel);
$level4Unlocked  = bulig_level_unlocked(5, $level3Complete, $assignedLevel);

$initial = strtoupper(substr((string) $_SESSION['full_name'], 0, 1)) ?: 'R';
$xp          = $summary['xp'];
$xpTarget    = 100;
$xpPercent   = min(100, (int) round(($xp / $xpTarget) * 100));
$badgesCount = count($summary['completed']) + count($summary2['completed']) + count($summary2b['completed']) + count($summary3['completed']) + count($summary4['completed']); // one badge per completed quest, all levels
$streakDays  = $summary['streakDays'];

$level2Done  = count($summary2['completed']);
$level2Total = $summary2['lessonsTotal'];

$level2bDone  = count($summary2b['completed']);
$level2bTotal = $summary2b['lessonsTotal'];

$level3Done  = count($summary3['completed']);
$level3Total = $summary3['lessonsTotal'];

$level4Done  = count($summary4['completed']);
$level4Total = $summary4['lessonsTotal'];
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

            <div class="who-you-are">
                <?= bulig_avatar_html($_SESSION['avatar_file'] ?? null, 'a-pupil', $initial, '../') ?>
                <div>
                    <div class="who-you-are-name"><?= htmlspecialchars($_SESSION['full_name'], ENT_QUOTES) ?></div>
                    <div class="who-you-are-sub">Grade <?= htmlspecialchars((string) ($_SESSION['grade_level'] ?? '—'), ENT_QUOTES) ?> · Assigned: <?= htmlspecialchars(bulig_level_label($assignedLevel), ENT_QUOTES) ?></div>
                </div>
            </div>

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
                <?php if ($level2Unlocked): ?>
                <a href="level2.php" class="action-card acc-pupil">
                    <span class="action-icon">🔤</span>
                    <h3>Level 2A: Phonological Awareness</h3>
                    <p>8 sound activities — isolating, blending, segmenting, and swapping sounds in words!</p>
                    <span class="action-go" style="color:var(--bulig-green-dark);"><?= $level2Done === 0 ? 'Start Quest →' : ($level2Done === $level2Total ? 'Review →' : 'Continue →') ?></span>
                </a>
                <?php else: ?>
                <div class="action-card is-soon">
                    <span class="action-icon">🔒</span>
                    <h3>Level 2A: Phonological Awareness</h3>
                    <p>Unlocks after you complete Level 1.</p>
                    <span class="pill-soon">Locked</span>
                </div>
                <?php endif; ?>
                <?php if ($level2bUnlocked): ?>
                <a href="level2b.php" class="action-card acc-pupil">
                    <span class="action-icon">🔡</span>
                    <h3>Level 2B: Phonological Awareness</h3>
                    <p>6 more sound quests — blending, segmenting, syllables, sentences, and rhymes!</p>
                    <span class="action-go" style="color:var(--bulig-green-dark);"><?= $level2bDone === 0 ? 'Start Quest →' : ($level2bDone === $level2bTotal ? 'Review →' : 'Continue →') ?></span>
                </a>
                <?php else: ?>
                <div class="action-card is-soon">
                    <span class="action-icon">🔒</span>
                    <h3>Level 2B: Phonological Awareness</h3>
                    <p>Unlocks after you complete Level 2A.</p>
                    <span class="pill-soon">Locked</span>
                </div>
                <?php endif; ?>
                <?php if ($level3Unlocked): ?>
                <a href="level3.php" class="action-card acc-pupil">
                    <span class="action-icon">📖</span>
                    <h3>Level 3: Word Recognition</h3>
                    <p>25 word lessons — CVC words, word families, consonant blends, and sight words!</p>
                    <span class="action-go" style="color:var(--bulig-green-dark);"><?= $level3Done === 0 ? 'Start Quest →' : ($level3Done === $level3Total ? 'Review →' : 'Continue →') ?></span>
                </a>
                <?php else: ?>
                <div class="action-card is-soon">
                    <span class="action-icon">🔒</span>
                    <h3>Level 3: Word Recognition</h3>
                    <p>Unlocks after you complete Level 2B.</p>
                    <span class="pill-soon">Locked</span>
                </div>
                <?php endif; ?>
                <?php if ($level4Unlocked && $pupilGrade): ?>
                <a href="level4.php" class="action-card acc-pupil">
                    <span class="action-icon">🎙️</span>
                    <h3>Level 4: Fluency (Grade <?= $pupilGrade ?>)</h3>
                    <p><?= $level4Total ?> reading passages made just for Grade <?= $pupilGrade ?> — practice reading aloud and grow your speed!</p>
                    <span class="action-go" style="color:var(--bulig-green-dark);"><?= $level4Done === 0 ? 'Start Quest →' : ($summary4['postDone'] ? 'Review →' : 'Continue →') ?></span>
                </a>
                <?php elseif ($level4Unlocked && !$pupilGrade): ?>
                <div class="action-card is-soon">
                    <span class="action-icon">🏫</span>
                    <h3>Level 4: Fluency</h3>
                    <p>Ask your teacher to set your grade level to unlock this.</p>
                    <span class="pill-soon">Needs Grade</span>
                </div>
                <?php else: ?>
                <div class="action-card is-soon">
                    <span class="action-icon">🔒</span>
                    <h3>Level 4: Fluency</h3>
                    <p>Unlocks after you complete Level 3.</p>
                    <span class="pill-soon">Locked</span>
                </div>
                <?php endif; ?>
                <a href="achievements.php" class="action-card">
                    <span class="action-icon">🎖️</span>
                    <h3>My Badges</h3>
                    <p>See every badge you've collected along the way.</p>
                    <span class="action-go" style="color:var(--bulig-green-dark);">View Badges →</span>
                </a>
            </div>

            <h2 class="section-title">🕒 Recent Activity</h2>
            <?php if (empty($recentActivity)): ?>
                <div class="recent-activity-empty">Nothing yet — finish your first activity to see it here!</div>
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
<script>
    document.querySelectorAll('.xp-bar-fill').forEach(function (bar) {
        requestAnimationFrame(function () { bar.style.width = bar.dataset.width + '%'; });
    });
</script>
</body>
</html>
