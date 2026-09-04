<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/level1_helpers.php';
require_once __DIR__ . '/../config/level2_helpers.php';
require_once __DIR__ . '/../config/level2b_helpers.php';
require_once __DIR__ . '/../config/level3_helpers.php';
require_pupil_login();
$activePupilNav = 'achievements';
$pageTitle = 'BULIG | Achievements';

// One real badge per Level 1 quest, marked earned once it's actually
// been completed in `pupil_progress`. A bonus badge appears once all
// 12 are done.
$badgeCatalog = [
    ['icon' => '🙋', 'name' => 'Meet & Greet'],
    ['icon' => '🕵️', 'name' => 'Mission Trail'],
    ['icon' => '🤝', 'name' => 'Polite Missions'],
    ['icon' => '🎨', 'name' => 'Word Toolkit'],
    ['icon' => '💬', 'name' => 'Picture Chat'],
    ['icon' => '🖍️', 'name' => 'Describe & Draw'],
    ['icon' => '🔗', 'name' => 'Story Chain'],
    ['icon' => '🖼️', 'name' => 'Picture Talk'],
    ['icon' => '⭐', 'name' => 'Recite & Shine'],
    ['icon' => '🎈', 'name' => 'Talk, Play & Share'],
    ['icon' => '🔤', 'name' => 'Word Friends'],
    ['icon' => '👅', 'name' => 'Tongue Twisters'],
];

try {
    $pdo     = get_db_connection();
    $summary = bulig_level1_summary($pdo, (int) $_SESSION['pupil_id']);
    $assignedLevel = bulig_pupil_current_level($pdo, (int) $_SESSION['pupil_id']);
} catch (Throwable $e) {
    $summary = ['completed' => []];
    $assignedLevel = 1;
}
$earnedSet = array_flip($summary['completed']);

$badges = [];
foreach ($badgeCatalog as $i => $b) {
    $lessonId = $i + 1;
    $badges[] = ['icon' => $b['icon'], 'name' => $b['name'], 'earned' => isset($earnedSet[$lessonId])];
}
$allEarned = count($summary['completed']) === count($badgeCatalog);
$badges[] = ['icon' => '🏆', 'name' => 'Level 1 Champ', 'earned' => $allEarned];

// Level 2A badges — one per phonological-awareness quest, same pattern as Level 1.
$badgeCatalog2 = [
    ['icon' => '🕵️', 'name' => 'Sound Detectives'],
    ['icon' => '🧩', 'name' => 'Sound Match-Up'],
    ['icon' => '🎯', 'name' => 'Odd Sound Out'],
    ['icon' => '🧱', 'name' => 'Blend It!'],
    ['icon' => '🔢', 'name' => 'Sound Counter'],
    ['icon' => '🪄', 'name' => 'Sound Vanish'],
    ['icon' => '➕', 'name' => 'Sound Booster'],
    ['icon' => '🔄', 'name' => 'Sound Swap'],
];

try {
    $level1Complete = bulig_level1_is_complete($pdo, (int) $_SESSION['pupil_id']);
    $summary2       = bulig_level2_summary($pdo, (int) $_SESSION['pupil_id']);
} catch (Throwable $e) {
    $level1Complete = false;
    $summary2       = ['completed' => []];
}
$earnedSet2 = array_flip($summary2['completed']);

$badges2 = [];
foreach ($badgeCatalog2 as $i => $b) {
    $lessonId = $i + 1;
    $badges2[] = ['icon' => $b['icon'], 'name' => $b['name'], 'earned' => isset($earnedSet2[$lessonId])];
}
$allEarned2 = count($summary2['completed']) === count($badgeCatalog2);
$badges2[] = ['icon' => '🏆', 'name' => 'Level 2A Champ', 'earned' => $allEarned2];

// Level 2B badges — one per unit, same pattern as Level 2A.
$badgeCatalog2b = [
    ['icon' => '🧩', 'name' => 'Sound Blenders'],
    ['icon' => '🔍', 'name' => 'Sound Splitters'],
    ['icon' => '✂️', 'name' => 'Syllable Sorters'],
    ['icon' => '🧱', 'name' => 'Word Builders'],
    ['icon' => '💬', 'name' => 'Sentence Detectives'],
    ['icon' => '🎵', 'name' => 'Rhyme Time'],
];

try {
    $level2aComplete = bulig_level2_is_complete($pdo, (int) $_SESSION['pupil_id']);
    $summary2b       = bulig_level2b_summary($pdo, (int) $_SESSION['pupil_id']);
} catch (Throwable $e) {
    $level2aComplete = false;
    $summary2b       = ['completed' => []];
}
$earnedSet2b = array_flip($summary2b['completed']);

$badges2b = [];
foreach ($badgeCatalog2b as $i => $b) {
    $lessonId = $i + 1;
    $badges2b[] = ['icon' => $b['icon'], 'name' => $b['name'], 'earned' => isset($earnedSet2b[$lessonId])];
}
$allEarned2b = count($summary2b['completed']) === count($badgeCatalog2b);
$badges2b[] = ['icon' => '🏆', 'name' => 'Level 2B Champ', 'earned' => $allEarned2b];

// Level 3 badges — one per Word Recognition lesson (25 lessons, same icons
// as js/level3.js's LESSONS map), same earned-flag pattern as Levels 1-2A.
$badgeCatalog3 = [
    ['icon' => '🐱', 'name' => 'Short \u2018a\u2019 Words'],   ['icon' => '🐔', 'name' => 'Short \u2018e\u2019 Words'],
    ['icon' => '🐷', 'name' => 'Short \u2018i\u2019 Words'],   ['icon' => '🐸', 'name' => 'Short \u2018o\u2019 Words'],
    ['icon' => '🐰', 'name' => 'Short \u2018u\u2019 Words'],   ['icon' => '🌧️', 'name' => '\u2018ai\u2019 & \u2018ea\u2019'],
    ['icon' => '🐐', 'name' => '\u2018oa\u2019 & \u2018oo\u2019'], ['icon' => '🎒', 'name' => '\u2018ack\u2019 & \u2018eck\u2019'],
    ['icon' => '🔔', 'name' => '\u2018all\u2019 & \u2018ell\u2019'], ['icon' => '🎺', 'name' => '\u2018-nk\u2019 & \u2018-sk\u2019'],
    ['icon' => '🧱', 'name' => 'Blend br-, bl-'],  ['icon' => '🦀', 'name' => 'Blend cr-, cl-'],
    ['icon' => '🐉', 'name' => 'Blend dr-'],       ['icon' => '🍟', 'name' => 'Blend fr-, fl-'],
    ['icon' => '🍇', 'name' => 'Blend gl-, gr-'],  ['icon' => '✈️', 'name' => 'Blend pl-, pr-'],
    ['icon' => '⛈️', 'name' => 'Blend st-, str-'], ['icon' => '🚿', 'name' => 'Blend sh-, sl-'],
    ['icon' => '🕷️', 'name' => 'Blend sp-, spr-, spl-'], ['icon' => '🚂', 'name' => 'Blend tr-'],
    ['icon' => '🎣', 'name' => 'Sight Words 1'],   ['icon' => '🐍', 'name' => 'Sight Words 2'],
    ['icon' => '🎡', 'name' => 'Sight Words 3'],   ['icon' => '📦', 'name' => 'Sight Words 4'],
    ['icon' => '🍂', 'name' => 'Sight Words 5'],
];

try {
    $level2Complete = bulig_level2b_is_complete($pdo, (int) $_SESSION['pupil_id']);
    $summary3       = bulig_level3_summary($pdo, (int) $_SESSION['pupil_id']);
} catch (Throwable $e) {
    $level2Complete = false;
    $summary3       = ['completed' => []];
}
$earnedSet3 = array_flip($summary3['completed']);

$badges3 = [];
foreach ($badgeCatalog3 as $i => $b) {
    $lessonId = $i + 1;
    $badges3[] = ['icon' => $b['icon'], 'name' => $b['name'], 'earned' => isset($earnedSet3[$lessonId])];
}
$allEarned3 = count($summary3['completed']) === count($badgeCatalog3);
$badges3[] = ['icon' => '🏆', 'name' => 'Level 3 Champ', 'earned' => $allEarned3];

// Level 4 badges — one per grade-specific fluency passage the pupil has
// practiced, plus a champ badge once the post-test score is recorded.
require_once __DIR__ . '/../config/level4_helpers.php';
try {
    $level3Complete = bulig_level3_is_complete($pdo, (int) $_SESSION['pupil_id']);
    $pupilGrade     = bulig_pupil_grade($pdo, (int) $_SESSION['pupil_id']);
    $summary4       = $pupilGrade ? bulig_level4_summary($pdo, (int) $_SESSION['pupil_id'], $pupilGrade) : ['completed' => [], 'lessonsTotal' => 0, 'postDone' => false];
} catch (Throwable $e) {
    $level3Complete = false;
    $pupilGrade     = null;
    $summary4       = ['completed' => [], 'lessonsTotal' => 0, 'postDone' => false];
}
$earnedSet4 = array_flip($summary4['completed']);

$level2Unlocked  = bulig_level_unlocked(2, $level1Complete, $assignedLevel);
$level2bUnlocked = bulig_level_unlocked(3, $level2aComplete, $assignedLevel);
$level3Unlocked  = bulig_level_unlocked(4, $level2Complete, $assignedLevel);
$level4Unlocked  = bulig_level_unlocked(5, $level3Complete, $assignedLevel);
$passageEmojis = ['📖','📗','📘','📙','📕','📓','📔','📒','📚','🗞️'];
$badges4 = [];
for ($i = 1; $i <= $summary4['lessonsTotal']; $i++) {
    $badges4[] = ['icon' => $passageEmojis[($i - 1) % count($passageEmojis)], 'name' => 'Passage ' . $i, 'earned' => isset($earnedSet4[$i])];
}
$badges4[] = ['icon' => '🎙️', 'name' => 'Level 4 Champ', 'earned' => $summary4['postDone']];

// Level 5 badges — one per grade-specific listening/vocabulary activity,
// using the REAL activity titles from config/level5_content.php (never
// invented names), plus a champ badge once all are done.
require_once __DIR__ . '/../config/level5_helpers.php';
require_once __DIR__ . '/../config/level5_content.php';
try {
    $level4Complete = bulig_level4_is_complete($pdo, (int) $_SESSION['pupil_id'], $pupilGrade);
    $summary5       = $pupilGrade ? bulig_level5_summary($pdo, (int) $_SESSION['pupil_id'], $pupilGrade) : ['completed' => [], 'lessonsTotal' => 0];
} catch (Throwable $e) {
    $level4Complete = false;
    $summary5       = ['completed' => [], 'lessonsTotal' => 0];
}
$earnedSet5 = array_flip($summary5['completed']);
$level5Unlocked = bulig_level_unlocked(6, $level4Complete, $assignedLevel);
$titles5 = $pupilGrade ? (bulig_level5_activity_titles()[$pupilGrade] ?? []) : [];
$badges5 = [];
foreach ($titles5 as $i => $title) {
    $num = $i + 1;
    $badges5[] = ['icon' => '👂', 'name' => $title, 'earned' => isset($earnedSet5[$num])];
}
if (!empty($titles5)) {
    $badges5[] = ['icon' => '🏆', 'name' => 'Level 5 Champ', 'earned' => count($summary5['completed']) === count($titles5)];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include __DIR__ . '/../partials/head_dash.php'; ?>
</head>
<body class="bg-pupil">
<div class="deco-layer" aria-hidden="true">
    <span class="deco d-star" style="top:10%; right:14%;">✦</span>
</div>
<div class="app-shell">
    <?php include __DIR__ . '/../partials/pupil_sidebar.php'; ?>
    <div class="content-wrap">
        <?php include __DIR__ . '/../partials/mobile_topbar.php'; ?>
        <main class="dash-main">
            <h2 class="section-title">🏆 Achievements</h2>
            <p style="color:var(--ink-soft); font-weight:700; margin-top:-6px;">Complete activities in Level 1 to unlock these badges.</p>
            <div class="badge-grid" style="margin-top:16px;">
                <?php foreach ($badges as $b): ?>
                <div class="badge-slot<?= $b['earned'] ? ' is-earned' : '' ?>">
                    <span class="badge-icon"><?= $b['icon'] ?></span>
                    <div class="badge-name"><?= htmlspecialchars($b['name'], ENT_QUOTES) ?></div>
                </div>
                <?php endforeach; ?>
            </div>

            <h2 class="section-title" style="margin-top:26px;">🔤 Level 2A Badges</h2>
            <?php if ($level2Unlocked): ?>
            <p style="color:var(--ink-soft); font-weight:700; margin-top:-6px;">Complete activities in Level 2A to unlock these badges.</p>
            <div class="badge-grid" style="margin-top:16px;">
                <?php foreach ($badges2 as $b): ?>
                <div class="badge-slot<?= $b['earned'] ? ' is-earned' : '' ?>">
                    <span class="badge-icon"><?= $b['icon'] ?></span>
                    <div class="badge-name"><?= htmlspecialchars($b['name'], ENT_QUOTES) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p style="color:var(--ink-soft); font-weight:700; margin-top:-6px;">🔒 Finish every Level 1 lesson to unlock Level 2A badges.</p>
            <?php endif; ?>

            <h2 class="section-title" style="margin-top:26px;">🔡 Level 2B Badges</h2>
            <?php if ($level2bUnlocked): ?>
            <p style="color:var(--ink-soft); font-weight:700; margin-top:-6px;">Complete activities in Level 2B to unlock these badges.</p>
            <div class="badge-grid" style="margin-top:16px;">
                <?php foreach ($badges2b as $b): ?>
                <div class="badge-slot<?= $b['earned'] ? ' is-earned' : '' ?>">
                    <span class="badge-icon"><?= $b['icon'] ?></span>
                    <div class="badge-name"><?= htmlspecialchars($b['name'], ENT_QUOTES) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p style="color:var(--ink-soft); font-weight:700; margin-top:-6px;">🔒 Finish every Level 2A lesson to unlock Level 2B badges.</p>
            <?php endif; ?>

            <h2 class="section-title" style="margin-top:26px;">📖 Level 3 Badges</h2>
            <?php if ($level3Unlocked): ?>
            <p style="color:var(--ink-soft); font-weight:700; margin-top:-6px;">Complete lessons in Level 3 to unlock these badges.</p>
            <div class="badge-grid" style="margin-top:16px;">
                <?php foreach ($badges3 as $b): ?>
                <div class="badge-slot<?= $b['earned'] ? ' is-earned' : '' ?>">
                    <span class="badge-icon"><?= $b['icon'] ?></span>
                    <div class="badge-name"><?= htmlspecialchars($b['name'], ENT_QUOTES) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p style="color:var(--ink-soft); font-weight:700; margin-top:-6px;">🔒 Finish every Level 2B lesson to unlock Level 3 badges.</p>
            <?php endif; ?>

            <h2 class="section-title" style="margin-top:26px;">🎙️ Level 4 Badges<?= $pupilGrade ? ' (Grade ' . $pupilGrade . ')' : '' ?></h2>
            <?php if ($level4Unlocked && $pupilGrade): ?>
            <p style="color:var(--ink-soft); font-weight:700; margin-top:-6px;">Practice each Grade <?= $pupilGrade ?> passage to unlock these badges.</p>
            <div class="badge-grid" style="margin-top:16px;">
                <?php foreach ($badges4 as $b): ?>
                <div class="badge-slot<?= $b['earned'] ? ' is-earned' : '' ?>">
                    <span class="badge-icon"><?= $b['icon'] ?></span>
                    <div class="badge-name"><?= htmlspecialchars($b['name'], ENT_QUOTES) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php elseif ($level4Unlocked): ?>
            <p style="color:var(--ink-soft); font-weight:700; margin-top:-6px;">🏫 Ask your teacher to set your grade level to unlock Level 4 badges.</p>
            <?php else: ?>
            <p style="color:var(--ink-soft); font-weight:700; margin-top:-6px;">🔒 Finish every Level 3 lesson to unlock Level 4 badges.</p>
            <?php endif; ?>

            <h2 class="section-title" style="margin-top:26px;">👂 Level 5 Badges<?= $pupilGrade ? ' (Grade ' . $pupilGrade . ')' : '' ?></h2>
            <?php if ($level5Unlocked && $pupilGrade): ?>
            <p style="color:var(--ink-soft); font-weight:700; margin-top:-6px;">Complete each Grade <?= $pupilGrade ?> activity to unlock these badges.</p>
            <div class="badge-grid" style="margin-top:16px;">
                <?php foreach ($badges5 as $b): ?>
                <div class="badge-slot<?= $b['earned'] ? ' is-earned' : '' ?>">
                    <span class="badge-icon"><?= $b['icon'] ?></span>
                    <div class="badge-name"><?= htmlspecialchars($b['name'], ENT_QUOTES) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php elseif ($level5Unlocked): ?>
            <p style="color:var(--ink-soft); font-weight:700; margin-top:-6px;">🏫 Ask your teacher to set your grade level to unlock Level 5 badges.</p>
            <?php else: ?>
            <p style="color:var(--ink-soft); font-weight:700; margin-top:-6px;">🔒 Finish every Level 4 passage to unlock Level 5 badges.</p>
            <?php endif; ?>
        </main>
    </div>
</div>
<script src="../js/sidebar.js"></script>
</body>
</html>
