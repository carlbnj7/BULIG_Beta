<?php
/**
 * BULIG — Level 5 (Listening Comprehension & Vocabulary Development)
 * pupil page. GRADE-GATED exactly like Level 4: only shows THIS pupil's
 * own grade_level's activities, read fresh from the database (never from
 * session — a teacher can change grade at any time and it must take
 * effect immediately).
 */
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/level1_helpers.php';
require_once __DIR__ . '/../config/level2_helpers.php';
require_once __DIR__ . '/../config/level2b_helpers.php';
require_once __DIR__ . '/../config/level3_helpers.php';
require_once __DIR__ . '/../config/level4_helpers.php';
require_once __DIR__ . '/../config/level5_helpers.php';
require_once __DIR__ . '/../config/level5_content.php';
require_pupil_login();

$activePupilNav = 'lessons';
$pageTitle = 'BULIG | Level 5 — Listening & Vocabulary';

$pupilId = (int) $_SESSION['pupil_id'];

try {
    $pdo            = get_db_connection();
    $grade          = bulig_pupil_grade($pdo, $pupilId);
    $level4Complete = bulig_level4_is_complete($pdo, $pupilId, $grade);
    $assignedLevel  = bulig_pupil_current_level($pdo, $pupilId);
    $summary        = $grade ? bulig_level5_summary($pdo, $pupilId, $grade) : null;
    $dbError        = false;
} catch (Throwable $e) {
    $dbError        = true;
    $level4Complete = false;
    $assignedLevel  = 1;
    $grade          = null;
    $summary        = null;
}

// Level 5 is locked until Level 4 is done — UNLESS a teacher directly
// assigned/advanced this pupil to Level 5 — AND until a grade is set
// (Level 5's content is grade-specific, same as Level 4).
if (!$dbError && (!bulig_level_unlocked(6, $level4Complete, $assignedLevel) || !$grade)) {
    header('Location: lessons.php');
    exit;
}

$titles     = $dbError ? [] : (bulig_level5_activity_titles()[$grade] ?? []);
$content    = $dbError ? [] : (bulig_level5_content()[$grade] ?? []);

$activities = [];
foreach ($titles as $i => $title) {
    $num = $i + 1;
    $activities[] = [
        'num'        => $num,
        'title'      => $title,
        'hasContent' => isset($content[$num]),
        'data'       => $content[$num] ?? null,
    ];
}

$initialState = $dbError ? null : [
    'grade'      => $grade,
    'xp'         => $summary['xp'],
    'completed'  => $summary['completed'],
    'activities' => $activities,
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include __DIR__ . '/../partials/head_dash.php'; ?>
<link rel="stylesheet" href="../css/level1.css">
</head>
<body class="bg-pupil">
<div class="deco-layer" aria-hidden="true">
    <span class="deco d-star" style="top:14%; left:12%;">✦</span>
    <span class="deco d-book" style="bottom:12%; right:10%;">📖</span>
</div>
<div class="app-shell">
    <?php include __DIR__ . '/../partials/pupil_sidebar.php'; ?>
    <div class="content-wrap">
        <?php include __DIR__ . '/../partials/mobile_topbar.php'; ?>
        <main class="dash-main l1-scope">
            <h2 class="section-title">👂 Level 5: Listening &amp; Vocabulary<?= $grade ? ' — Grade ' . $grade : '' ?></h2>

            <?php if ($dbError || empty($activities)): ?>
                <div class="coming-soon">
                    <span class="cs-icon">⚠️</span>
                    <h1>Couldn't load your activities</h1>
                    <p>There was a problem connecting to the database. Please check the connection settings and try again.</p>
                </div>
            <?php else: ?>
                <div id="mapRoot"><!-- filled by js/level5.js --></div>
            <?php endif; ?>
        </main>
    </div>
</div>
<script src="../js/sidebar.js"></script>
<?php if (!$dbError && !empty($activities)): ?>
<script>
  const SERVER_STATE = <?php echo json_encode($initialState, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
  const SAVE_URL = 'level5_save.php';
</script>
<script src="../js/level5.js"></script>
<?php endif; ?>
</body>
</html>
