<?php
/**
 * BULIG — Level 5 save endpoint. Pupil-scored (self-answered quiz),
 * grade-gated. XP is decided server-side, never trusted from the client.
 */
declare(strict_types=1);
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/level4_helpers.php';
require_once __DIR__ . '/../config/level5_helpers.php';
require_once __DIR__ . '/../config/level5_content.php';

require_pupil_login();

$pdo     = get_db_connection();
$pupilId = (int) $_SESSION['pupil_id'];

$grade = bulig_pupil_grade($pdo, $pupilId);
if ($grade === null) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'No grade assigned yet — ask your teacher to set your grade.']);
    exit;
}

if (!bulig_level4_is_complete($pdo, $pupilId, $grade)) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Level 4 must be completed first.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input) || ($input['type'] ?? '') !== 'activity') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Bad request.']);
    exit;
}

$activityNum = (int) ($input['activity_num'] ?? 0);
$lessonsTotal = bulig_level5_lessons_total($grade);

if ($activityNum < 1 || $activityNum > $lessonsTotal || !bulig_level5_activity_has_content($grade, $activityNum)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Invalid or not-yet-available activity.']);
    exit;
}

try {
    $check = $pdo->prepare(
        'SELECT id FROM pupil_progress WHERE pupil_id = :p AND level_id = ' . BULIG_L5_LEVEL_ID . ' AND lesson_id = :l'
    );
    $check->execute(['p' => $pupilId, 'l' => $activityNum]);

    if ($check->fetch()) {
        echo json_encode(['ok' => true, 'xp_awarded' => 0, 'already_done' => true]);
    } else {
        $ins = $pdo->prepare(
            'INSERT INTO pupil_progress (pupil_id, level_id, lesson_id, xp_earned) VALUES (:p, ' . BULIG_L5_LEVEL_ID . ', :l, :xp)'
        );
        $ins->execute(['p' => $pupilId, 'l' => $activityNum, 'xp' => BULIG_L5_XP_PER_ACTIVITY]);
        echo json_encode(['ok' => true, 'xp_awarded' => BULIG_L5_XP_PER_ACTIVITY, 'already_done' => false]);
    }
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Could not save progress.']);
}
