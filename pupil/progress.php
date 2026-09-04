<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/level1_helpers.php';
require_once __DIR__ . '/../config/level2_helpers.php';
require_once __DIR__ . '/../config/level2b_helpers.php';
require_once __DIR__ . '/../config/level3_helpers.php';
require_once __DIR__ . '/../config/level4_helpers.php';
require_once __DIR__ . '/../config/level5_helpers.php';
require_pupil_login();
$activePupilNav = 'progress';
$pageTitle = 'BULIG | My Progress';
$pupilId = (int) $_SESSION['pupil_id'];

try {
    $pdo            = get_db_connection();
    $summary        = bulig_level1_summary($pdo, $pupilId);
    $level1Complete = bulig_level1_is_complete($pdo, $pupilId);
    $summary2       = bulig_level2_summary($pdo, $pupilId);
    $level2aComplete = bulig_level2_is_complete($pdo, $pupilId);
    $summary2b      = bulig_level2b_summary($pdo, $pupilId);
    $level2bComplete = bulig_level2b_is_complete($pdo, $pupilId);
    $summary3      = bulig_level3_summary($pdo, $pupilId);
    $level3Complete = bulig_level3_is_complete($pdo, $pupilId);
    $pupilGrade     = bulig_pupil_grade($pdo, $pupilId);
    $assignedLevel  = bulig_pupil_current_level($pdo, $pupilId);
    $summary4       = $pupilGrade ? bulig_level4_summary($pdo, $pupilId, $pupilGrade) : ['xp' => 0, 'completed' => [], 'lessonsTotal' => 0, 'preDone' => false, 'postDone' => false, 'preScore' => null, 'postScore' => null];
    $level4Complete = bulig_level4_is_complete($pdo, $pupilId, $pupilGrade);
    $summary5       = $pupilGrade ? bulig_level5_summary($pdo, $pupilId, $pupilGrade) : ['xp' => 0, 'completed' => [], 'lessonsTotal' => 0];
} catch (Throwable $e) {
    $summary        = ['xp' => 0, 'completed' => [], 'lessonsTotal' => 12];
    $level1Complete = false;
    $summary2       = ['xp' => 0, 'completed' => [], 'lessonsTotal' => BULIG_L2_LESSON_COUNT];
    $level2aComplete = false;
    $summary2b      = ['xp' => 0, 'completed' => [], 'lessonsTotal' => BULIG_L2B_LESSON_COUNT];
    $level2bComplete = false;
    $summary3      = ['xp' => 0, 'completed' => [], 'lessonsTotal' => BULIG_L3_LESSON_COUNT];
    $level3Complete = false;
    $pupilGrade     = null;
    $assignedLevel  = 1;
    $summary4       = ['xp' => 0, 'completed' => [], 'lessonsTotal' => 0, 'preDone' => false, 'postDone' => false, 'preScore' => null, 'postScore' => null];
    $level4Complete = false;
    $summary5       = ['xp' => 0, 'completed' => [], 'lessonsTotal' => 0];
}
$level2Unlocked  = bulig_level_unlocked(2, $level1Complete, $assignedLevel);
$level2bUnlocked = bulig_level_unlocked(3, $level2aComplete, $assignedLevel);
$level3Unlocked  = bulig_level_unlocked(4, $level2bComplete, $assignedLevel);
$level4Unlocked  = bulig_level_unlocked(5, $level3Complete, $assignedLevel);
$level5Unlocked  = bulig_level_unlocked(6, $level4Complete, $assignedLevel);
$xp = $summary['xp']; $xpTarget = 100;
$xpPercent = min(100, (int) round(($xp / $xpTarget) * 100));
$lessonsDone = count($summary['completed']); $lessonsTotal = $summary['lessonsTotal'];
$lessonPercent = min(100, (int) round(($lessonsDone / $lessonsTotal) * 100));

$xp2 = $summary2['xp']; $xp2Target = 100;
$xp2Percent = min(100, (int) round(($xp2 / $xp2Target) * 100));
$lessons2Done = count($summary2['completed']); $lessons2Total = $summary2['lessonsTotal'];
$lesson2Percent = min(100, (int) round(($lessons2Done / $lessons2Total) * 100));

$xp2b = $summary2b['xp']; $xp2bTarget = 100;
$xp2bPercent = min(100, (int) round(($xp2b / $xp2bTarget) * 100));
$lessons2bDone = count($summary2b['completed']); $lessons2bTotal = $summary2b['lessonsTotal'];
$lesson2bPercent = min(100, (int) round(($lessons2bDone / $lessons2bTotal) * 100));

$xp3 = $summary3['xp']; $xp3Target = 100;
$xp3Percent = min(100, (int) round(($xp3 / $xp3Target) * 100));
$lessons3Done = count($summary3['completed']); $lessons3Total = $summary3['lessonsTotal'];
$lesson3Percent = min(100, (int) round(($lessons3Done / $lessons3Total) * 100));

$xp4 = $summary4['xp']; $xp4Target = 100;
$xp4Percent = min(100, (int) round(($xp4 / $xp4Target) * 100));
$lessons4Done = count($summary4['completed']); $lessons4Total = max(1, $summary4['lessonsTotal']);
$lesson4Percent = min(100, (int) round(($lessons4Done / $lessons4Total) * 100));

$xp5 = $summary5['xp']; $xp5Target = 100;
$xp5Percent = min(100, (int) round(($xp5 / $xp5Target) * 100));
$lessons5Done = count($summary5['completed']); $lessons5Total = max(1, $summary5['lessonsTotal']);
$lesson5Percent = min(100, (int) round(($lessons5Done / $lessons5Total) * 100));
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

            <h2 class="section-title" style="margin-top:26px;">🔤 Level 2A: Phonological Awareness</h2>
            <?php if ($level2Unlocked): ?>
            <div class="stat-grid" style="grid-template-columns: repeat(2, 1fr);">
                <div class="stat-card">
                    <span class="stat-icon">⭐</span>
                    <div class="stat-value"><?= $xp2 ?> XP</div>
                    <div class="stat-label">Total Level 2A Experience</div>
                    <div class="xp-bar-track"><div class="xp-bar-fill" data-width="<?= $xp2Percent ?>"></div></div>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">📘</span>
                    <div class="stat-value"><?= $lessons2Done ?> / <?= $lessons2Total ?></div>
                    <div class="stat-label">Level 2A Lessons Completed</div>
                    <div class="xp-bar-track"><div class="xp-bar-fill" data-width="<?= $lesson2Percent ?>"></div></div>
                </div>
            </div>
            <p style="color:var(--ink-soft); font-weight:700;">Your progress bars fill up as you complete each Level 2A sound activity.</p>
            <?php else: ?>
            <p style="color:var(--ink-soft); font-weight:700;">🔒 Finish every Level 1 lesson to unlock Level 2A progress tracking.</p>
            <?php endif; ?>

            <h2 class="section-title" style="margin-top:26px;">🔡 Level 2B: Phonological Awareness</h2>
            <?php if ($level2bUnlocked): ?>
            <div class="stat-grid" style="grid-template-columns: repeat(2, 1fr);">
                <div class="stat-card">
                    <span class="stat-icon">⭐</span>
                    <div class="stat-value"><?= $xp2b ?> XP</div>
                    <div class="stat-label">Total Level 2B Experience</div>
                    <div class="xp-bar-track"><div class="xp-bar-fill" data-width="<?= $xp2bPercent ?>"></div></div>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">📘</span>
                    <div class="stat-value"><?= $lessons2bDone ?> / <?= $lessons2bTotal ?></div>
                    <div class="stat-label">Level 2B Lessons Completed</div>
                    <div class="xp-bar-track"><div class="xp-bar-fill" data-width="<?= $lesson2bPercent ?>"></div></div>
                </div>
            </div>
            <p style="color:var(--ink-soft); font-weight:700;">Your progress bars fill up as you complete each Level 2B sound activity.</p>
            <?php else: ?>
            <p style="color:var(--ink-soft); font-weight:700;">🔒 Finish every Level 2A lesson to unlock Level 2B progress tracking.</p>
            <?php endif; ?>

            <h2 class="section-title" style="margin-top:26px;">📖 Level 3: Word Recognition</h2>
            <?php if ($level3Unlocked): ?>
            <div class="stat-grid" style="grid-template-columns: repeat(2, 1fr);">
                <div class="stat-card">
                    <span class="stat-icon">⭐</span>
                    <div class="stat-value"><?= $xp3 ?> XP</div>
                    <div class="stat-label">Total Level 3 Experience</div>
                    <div class="xp-bar-track"><div class="xp-bar-fill" data-width="<?= $xp3Percent ?>"></div></div>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">📘</span>
                    <div class="stat-value"><?= $lessons3Done ?> / <?= $lessons3Total ?></div>
                    <div class="stat-label">Level 3 Lessons Completed</div>
                    <div class="xp-bar-track"><div class="xp-bar-fill" data-width="<?= $lesson3Percent ?>"></div></div>
                </div>
            </div>
            <p style="color:var(--ink-soft); font-weight:700;">Your progress bars fill up as you complete each Level 3 word lesson.</p>
            <?php else: ?>
            <p style="color:var(--ink-soft); font-weight:700;">🔒 Finish every Level 2B lesson to unlock Level 3 progress tracking.</p>
            <?php endif; ?>

            <h2 class="section-title" style="margin-top:26px;">🎙️ Level 4: Fluency<?= $pupilGrade ? ' (Grade ' . $pupilGrade . ')' : '' ?></h2>
            <?php if ($level4Unlocked && $pupilGrade): ?>
            <div class="stat-grid" style="grid-template-columns: repeat(2, 1fr);">
                <div class="stat-card">
                    <span class="stat-icon">⭐</span>
                    <div class="stat-value"><?= $xp4 ?> XP</div>
                    <div class="stat-label">Total Level 4 Experience</div>
                    <div class="xp-bar-track"><div class="xp-bar-fill" data-width="<?= $xp4Percent ?>"></div></div>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">📘</span>
                    <div class="stat-value"><?= $lessons4Done ?> / <?= $summary4['lessonsTotal'] ?></div>
                    <div class="stat-label">Passages Practiced</div>
                    <div class="xp-bar-track"><div class="xp-bar-fill" data-width="<?= $lesson4Percent ?>"></div></div>
                </div>
            </div>
            <?php if ($summary4['preScore']): ?>
                <p style="color:var(--ink-soft); font-weight:700;">
                    📝 Pre-test score (recorded by your teacher): <strong><?= htmlspecialchars((string) $summary4['preScore']['oralReadingScore'], ENT_QUOTES) ?>%</strong>
                    — <?= htmlspecialchars((string) $summary4['preScore']['readingLevel'], ENT_QUOTES) ?> level,
                    <?= htmlspecialchars((string) $summary4['preScore']['wpm'], ENT_QUOTES) ?> words per minute.
                </p>
            <?php else: ?>
                <p style="color:var(--ink-soft); font-weight:700;">📝 Your teacher hasn't recorded your pre-test reading score yet.</p>
            <?php endif; ?>
            <?php if ($summary4['postScore']): ?>
                <p style="color:var(--ink-soft); font-weight:700;">
                    🏁 Post-test score (recorded by your teacher): <strong><?= htmlspecialchars((string) $summary4['postScore']['oralReadingScore'], ENT_QUOTES) ?>%</strong>
                    — <?= htmlspecialchars((string) $summary4['postScore']['readingLevel'], ENT_QUOTES) ?> level,
                    <?= htmlspecialchars((string) $summary4['postScore']['wpm'], ENT_QUOTES) ?> words per minute.
                </p>
            <?php endif; ?>
            <?php elseif ($level4Unlocked): ?>
            <p style="color:var(--ink-soft); font-weight:700;">🏫 Ask your teacher to set your grade level to unlock Level 4 progress tracking.</p>
            <?php else: ?>
            <p style="color:var(--ink-soft); font-weight:700;">🔒 Finish every Level 3 lesson to unlock Level 4 progress tracking.</p>
            <?php endif; ?>

            <h2 class="section-title" style="margin-top:26px;">👂 Level 5: Listening &amp; Vocabulary<?= $pupilGrade ? ' (Grade ' . $pupilGrade . ')' : '' ?></h2>
            <?php if ($level5Unlocked && $pupilGrade): ?>
            <div class="stat-grid" style="grid-template-columns: repeat(2, 1fr);">
                <div class="stat-card">
                    <span class="stat-icon">⭐</span>
                    <div class="stat-value"><?= $xp5 ?> XP</div>
                    <div class="stat-label">Total Level 5 Experience</div>
                    <div class="xp-bar-track"><div class="xp-bar-fill" data-width="<?= $xp5Percent ?>"></div></div>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">📘</span>
                    <div class="stat-value"><?= $lessons5Done ?> / <?= $summary5['lessonsTotal'] ?></div>
                    <div class="stat-label">Activities Completed</div>
                    <div class="xp-bar-track"><div class="xp-bar-fill" data-width="<?= $lesson5Percent ?>"></div></div>
                </div>
            </div>
            <p style="color:var(--ink-soft); font-weight:700;">Your progress bars fill up as you complete each Level 5 listening &amp; vocabulary activity.</p>
            <?php elseif ($level5Unlocked): ?>
            <p style="color:var(--ink-soft); font-weight:700;">🏫 Ask your teacher to set your grade level to unlock Level 5 progress tracking.</p>
            <?php else: ?>
            <p style="color:var(--ink-soft); font-weight:700;">🔒 Finish every Level 4 passage to unlock Level 5 progress tracking.</p>
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
