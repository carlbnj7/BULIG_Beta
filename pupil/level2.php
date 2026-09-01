<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/level1_helpers.php';
require_once __DIR__ . '/../config/level2_helpers.php';
require_once __DIR__ . '/../config/level2b_helpers.php';
require_once __DIR__ . '/../config/level3_helpers.php';
require_pupil_login();

$activePupilNav = 'lessons';
$pageTitle = 'BULIG | Level 2A — Phonological Awareness';

$pupilId = (int) $_SESSION['pupil_id'];

try {
    $pdo            = get_db_connection();
    $level1Complete = bulig_level1_is_complete($pdo, $pupilId);
    $assignedLevel  = bulig_pupil_current_level($pdo, $pupilId);
    $summary        = bulig_level2_summary($pdo, $pupilId);
    $dbError        = false;
} catch (Throwable $e) {
    $dbError        = true;
    $level1Complete = false;
    $assignedLevel  = 1;
    $summary        = ['xp' => 0, 'completed' => [], 'preDone' => false, 'postDone' => false, 'preAnswers' => new stdClass(), 'postAnswers' => new stdClass()];
}

// Level 2A is locked until every Level 1 quest is done — UNLESS a
// teacher directly assigned/advanced this pupil to Level 2A or beyond
// (bulig_level_unlocked()), e.g. a fluent pupil placed straight into a
// higher level. Otherwise, send pupils back to My Lessons instead of
// letting them view an empty/broken map.
if (!$dbError && !bulig_level_unlocked(2, $level1Complete, $assignedLevel)) {
    header('Location: lessons.php');
    exit;
}

$initialState = [
    'xp'          => $summary['xp'],
    'completed'   => $summary['completed'],
    'preDone'     => $summary['preDone'],
    'postDone'    => $summary['postDone'],
    'preAnswers'  => $summary['preAnswers'],
    'postAnswers' => $summary['postAnswers'],
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
    <span class="deco d-letter" style="bottom:12%; right:10%;">Aa</span>
</div>
<div class="app-shell">
    <?php include __DIR__ . '/../partials/pupil_sidebar.php'; ?>
    <div class="content-wrap">
        <?php include __DIR__ . '/../partials/mobile_topbar.php'; ?>
        <main class="dash-main l1-scope">
            <h2 class="section-title">🔤 Level 2A: Phonological Awareness</h2>

            <?php if ($dbError): ?>
                <div class="coming-soon">
                    <span class="cs-icon">⚠️</span>
                    <h1>Couldn't load your progress</h1>
                    <p>There was a problem connecting to the database. Please check the connection settings and try again.</p>
                </div>
            <?php else: ?>
                <div id="mapRoot"><!-- filled by js/level2.js --></div>
            <?php endif; ?>
        </main>
    </div>
</div>
<script src="../js/sidebar.js"></script>
<?php if (!$dbError): ?>
<script>
  const SERVER_STATE = <?php echo json_encode($initialState, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
  const SAVE_URL = 'level2_save.php';
</script>
<script src="../js/level2.js"></script>
<?php endif; ?>
</body>
</html>
