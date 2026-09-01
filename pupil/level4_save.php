<?php
/**
 * BULIG — Level 4 pupil-side progress writer.
 * Mirrors pupil/level3_save.php, but only ever handles the intervention
 * (practice) passages a PUPIL can mark done themselves. Pre-/post-test
 * fluency scores are entered by the teacher via
 * teacher/save_fluency_score.php — this endpoint deliberately refuses
 * type 'pre'/'post' so a pupil can't self-score their own fluency test
 * from the browser.
 */
declare(strict_types=1);
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/level1_helpers.php';
require_once __DIR__ . '/../config/level3_helpers.php';
require_once __DIR__ . '/../config/level4_helpers.php';
require_pupil_login();

$pdo     = get_db_connection();
$pupilId = (int) $_SESSION['pupil_id'];

if (!bulig_level3_is_complete($pdo, $pupilId)) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Level 4 is locked until Level 3 is complete.']);
    exit;
}

$grade = bulig_pupil_grade($pdo, $pupilId);
if (!$grade) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Ask your teacher to set your grade level first.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input) || ($input['type'] ?? '') !== 'passage') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Bad request.']);
    exit;
}

$passageNum = (int) ($input['passage_num'] ?? 0);
$lessonsTotal = BULIG_L4_INTERVENTION_COUNTS[$grade] ?? 0;

if ($passageNum < 1 || $passageNum > $lessonsTotal) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Invalid passage number.']);
    exit;
}

try {
    $check = $pdo->prepare('SELECT id FROM pupil_progress WHERE pupil_id = :p AND level_id = ' . BULIG_L4_LEVEL_ID . ' AND lesson_id = :l');
    $check->execute(['p' => $pupilId, 'l' => $passageNum]);

    if ($check->fetch()) {
        echo json_encode(['ok' => true, 'xp_awarded' => 0, 'already_done' => true]);
    } else {
        $ins = $pdo->prepare(
            'INSERT INTO pupil_progress (pupil_id, level_id, lesson_id, xp_earned) VALUES (:p, ' . BULIG_L4_LEVEL_ID . ', :l, :xp)'
        );
        $ins->execute(['p' => $pupilId, 'l' => $passageNum, 'xp' => BULIG_L4_XP_PER_PASSAGE]);
        echo json_encode(['ok' => true, 'xp_awarded' => BULIG_L4_XP_PER_PASSAGE, 'already_done' => false]);
    }
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Could not save progress.']);
}
