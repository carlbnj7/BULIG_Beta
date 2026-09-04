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
$activePupilNav = 'lessons';
$pageTitle = 'BULIG | My Lessons';

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
    $level4Complete = bulig_level4_is_complete($pdo, $pupilId, $pupilGrade);
    $assignedLevel  = bulig_pupil_current_level($pdo, $pupilId);
} catch (Throwable $e) {
    $summary        = ['xp' => 0, 'completed' => [], 'streakDays' => 0];
    $level1Complete = false;
    $summary2       = ['xp' => 0, 'completed' => [], 'lessonsTotal' => BULIG_L2_LESSON_COUNT];
    $level2Complete = false;
    $summary2b      = ['xp' => 0, 'completed' => [], 'lessonsTotal' => BULIG_L2B_LESSON_COUNT];
    $level2bComplete = false;
    $summary3       = ['xp' => 0, 'completed' => [], 'lessonsTotal' => BULIG_L3_LESSON_COUNT];
    $level3Complete = false;
    $level4Complete = false;
    $pupilGrade     = null;
    $assignedLevel  = 1;
}
$level2Unlocked  = bulig_level_unlocked(2, $level1Complete, $assignedLevel);
$level2bUnlocked = bulig_level_unlocked(3, $level2Complete, $assignedLevel);
$level3Unlocked  = bulig_level_unlocked(4, $level2bComplete, $assignedLevel);
$level4Unlocked  = bulig_level_unlocked(5, $level3Complete, $assignedLevel);
$level5Unlocked  = bulig_level_unlocked(6, $level4Complete, $assignedLevel);
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
                <?php if ($level2Unlocked): ?>
                <a href="level2.php" class="action-card acc-pupil">
                    <span class="action-icon">🔤</span>
                    <h3>Level 2A: Phonological Awareness</h3>
                    <p>8 lessons — isolating, identifying, blending, segmenting, deleting, adding, and swapping sounds.</p>
                    <span class="action-go">Enter Level 2A →</span>
                </a>
                <?php else: ?>
                <div class="action-card is-soon">
                    <span class="action-icon">🔒</span>
                    <h3>Level 2A: Phonological Awareness</h3>
                    <p>Unlocks after finishing Level 1.</p>
                    <span class="pill-soon">Locked</span>
                </div>
                <?php endif; ?>
                <?php if ($level2bUnlocked): ?>
                <a href="level2b.php" class="action-card acc-pupil">
                    <span class="action-icon">🔡</span>
                    <h3>Level 2B: Phonological Awareness</h3>
                    <p>6 lessons — blending and segmenting onsets/rimes, syllables, sentences, and rhymes.</p>
                    <span class="action-go">Enter Level 2B →</span>
                </a>
                <?php else: ?>
                <div class="action-card is-soon">
                    <span class="action-icon">🔒</span>
                    <h3>Level 2B: Phonological Awareness</h3>
                    <p>Unlocks after finishing Level 2A.</p>
                    <span class="pill-soon">Locked</span>
                </div>
                <?php endif; ?>
                <?php if ($level3Unlocked): ?>
                <a href="level3.php" class="action-card acc-pupil">
                    <span class="action-icon">📖</span>
                    <h3>Level 3: Word Recognition</h3>
                    <p>25 lessons — CVC words, word families, consonant blends, and sight words.</p>
                    <span class="action-go">Enter Level 3 →</span>
                </a>
                <?php else: ?>
                <div class="action-card is-soon">
                    <span class="action-icon">🔒</span>
                    <h3>Level 3: Word Recognition</h3>
                    <p>Unlocks after finishing Level 2B.</p>
                    <span class="pill-soon">Locked</span>
                </div>
                <?php endif; ?>
                <?php if ($level4Unlocked && $pupilGrade): ?>
                <a href="level4.php" class="action-card acc-pupil">
                    <span class="action-icon">🎙️</span>
                    <h3>Level 4: Fluency (Grade <?= $pupilGrade ?>)</h3>
                    <p>Reading passages picked just for your grade — practice reading aloud with your teacher.</p>
                    <span class="action-go">Enter Level 4 →</span>
                </a>
                <?php elseif ($level4Unlocked): ?>
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
                    <p>Unlocks after finishing Level 3.</p>
                    <span class="pill-soon">Locked</span>
                </div>
                <?php endif; ?>
                <?php if ($level5Unlocked && $pupilGrade): ?>
                <a href="level5.php" class="action-card acc-pupil">
                    <span class="action-icon">👂</span>
                    <h3>Level 5: Listening &amp; Vocabulary (Grade <?= $pupilGrade ?>)</h3>
                    <p>20 activities picked just for your grade — listening comprehension and vocabulary building.</p>
                    <span class="action-go">Enter Level 5 →</span>
                </a>
                <?php elseif ($level5Unlocked): ?>
                <div class="action-card is-soon">
                    <span class="action-icon">🏫</span>
                    <h3>Level 5: Listening &amp; Vocabulary</h3>
                    <p>Ask your teacher to set your grade level to unlock this.</p>
                    <span class="pill-soon">Needs Grade</span>
                </div>
                <?php else: ?>
                <div class="action-card is-soon">
                    <span class="action-icon">🔒</span>
                    <h3>Level 5: Listening &amp; Vocabulary</h3>
                    <p>Unlocks after finishing Level 4.</p>
                    <span class="pill-soon">Locked</span>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>
<script src="../js/sidebar.js"></script>
</body>
</html>
