<?php
/**
 * BULIG — Level 4 (Reading Fluency) pupil page.
 * GRADE-GATED: only shows the passages for THIS pupil's own grade_level,
 * read fresh from the database (never from session — a teacher can
 * change a pupil's grade at any time on teacher/pupils.php and that
 * must take effect immediately, not just on next login).
 */
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/level1_helpers.php';
require_once __DIR__ . '/../config/level2_helpers.php';
require_once __DIR__ . '/../config/level2b_helpers.php';
require_once __DIR__ . '/../config/level3_helpers.php';
require_once __DIR__ . '/../config/level4_helpers.php';
require_once __DIR__ . '/../config/level4_content.php';
require_pupil_login();

$activePupilNav = 'lessons';
$pageTitle = 'BULIG | Level 4 — Fluency';

$pupilId = (int) $_SESSION['pupil_id'];

try {
    $pdo            = get_db_connection();
    $level3Complete = bulig_level3_is_complete($pdo, $pupilId);
    $assignedLevel  = bulig_pupil_current_level($pdo, $pupilId);
    $grade          = bulig_pupil_grade($pdo, $pupilId);
    $summary        = $grade ? bulig_level4_summary($pdo, $pupilId, $grade) : null;
    $dbError        = false;
} catch (Throwable $e) {
    $dbError        = true;
    $level3Complete = false;
    $assignedLevel  = 1;
    $grade          = null;
    $summary        = null;
}

// Level 4 is locked until Level 3 is done — UNLESS a teacher directly
// assigned/advanced this pupil to Level 4 — AND until the teacher has
// separately set a grade (Level 4's content is grade-specific, so that
// gate always applies regardless of assigned level). Otherwise, send
// pupils back to My Lessons instead of an empty page.
if (!$dbError && (!bulig_level_unlocked(5, $level3Complete, $assignedLevel) || !$grade)) {
    header('Location: lessons.php');
    exit;
}

$content = $dbError ? null : (bulig_level4_content()[$grade] ?? null);

$initialState = $dbError ? null : [
    'grade'      => $grade,
    'xp'         => $summary['xp'],
    'completed'  => $summary['completed'],
    'preDone'    => $summary['preDone'],
    'postDone'   => $summary['postDone'],
    'preScore'   => $summary['preScore'],
    'postScore'  => $summary['postScore'],
    'content'    => $content,
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
            <h2 class="section-title">🎙️ Level 4: Fluency<?= $grade ? ' — Grade ' . $grade : '' ?></h2>

            <?php if ($dbError || !$content): ?>
                <div class="coming-soon">
                    <span class="cs-icon">⚠️</span>
                    <h1>Couldn't load your reading passages</h1>
                    <p>There was a problem connecting to the database. Please check the connection settings and try again.</p>
                </div>
            <?php else: ?>
                <div id="mapRoot"><!-- filled by js/level4.js --></div>
            <?php endif; ?>
        </main>
    </div>
</div>
<script src="../js/sidebar.js"></script>
<?php if (!$dbError && $content): ?>
<script>
  const SERVER_STATE = <?php echo json_encode($initialState, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
  const SAVE_URL = 'level4_save.php';
</script>
<script src="../js/level4.js"></script>
<?php endif; ?>
</body>
</html>
