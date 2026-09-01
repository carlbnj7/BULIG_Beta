<?php
/**
 * BULIG — Level 3 progress writer.
 * Mirrors pupil/level2_save.php. Called via fetch() from js/level3.js
 * whenever a pupil finishes a Level 3 lesson or submits an assessment.
 * XP amounts are decided here, not trusted from the client, so a pupil
 * can't inflate their own score from the browser.
 */
declare(strict_types=1);
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/level1_helpers.php';
require_once __DIR__ . '/../config/level2_helpers.php';
require_once __DIR__ . '/../config/level2b_helpers.php';
require_once __DIR__ . '/../config/level3_helpers.php';
require_pupil_login();

$pdo     = get_db_connection();
$pupilId = (int) $_SESSION['pupil_id'];

// Server-side lock: Level 3 can't be saved to unless Level 2B is fully
// done, even if someone calls this endpoint directly.
if (!bulig_level2b_is_complete($pdo, $pupilId)) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Level 3 is locked until Level 2B is complete.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Bad request.']);
    exit;
}

$type = $input['type'] ?? '';

try {
    if ($type === 'lesson') {
        $lessonId = (int) ($input['lesson_id'] ?? 0);
        if ($lessonId < 1 || $lessonId > BULIG_L3_LESSON_COUNT) {
            throw new InvalidArgumentException('Invalid lesson id.');
        }

        $check = $pdo->prepare('SELECT id FROM pupil_progress WHERE pupil_id = :p AND level_id = ' . BULIG_L3_LEVEL_ID . ' AND lesson_id = :l');
        $check->execute(['p' => $pupilId, 'l' => $lessonId]);

        if ($check->fetch()) {
            echo json_encode(['ok' => true, 'xp_awarded' => 0, 'already_done' => true]);
        } else {
            $ins = $pdo->prepare(
                'INSERT INTO pupil_progress (pupil_id, level_id, lesson_id, xp_earned) VALUES (:p, ' . BULIG_L3_LEVEL_ID . ', :l, :xp)'
            );
            $ins->execute(['p' => $pupilId, 'l' => $lessonId, 'xp' => BULIG_L3_XP_PER_LESSON]);
            echo json_encode(['ok' => true, 'xp_awarded' => BULIG_L3_XP_PER_LESSON, 'already_done' => false]);
        }

    } elseif ($type === 'pre' || $type === 'post') {
        $slot    = $type === 'pre' ? 0 : 100;
        $answers = json_encode($input['answers'] ?? new stdClass());

        if ($type === 'post') {
            $summary = bulig_level3_summary($pdo, $pupilId);
            if (count($summary['completed']) < BULIG_L3_LESSON_COUNT) {
                throw new InvalidArgumentException('Post-assessment unlocks after all Level 3 lessons are complete.');
            }
        }

        $check = $pdo->prepare('SELECT id FROM pupil_progress WHERE pupil_id = :p AND level_id = ' . BULIG_L3_LEVEL_ID . ' AND lesson_id = :l');
        $check->execute(['p' => $pupilId, 'l' => $slot]);
        $existing = $check->fetch();

        if ($existing) {
            $upd = $pdo->prepare('UPDATE pupil_progress SET answer_text = :a WHERE id = :id');
            $upd->execute(['a' => $answers, 'id' => $existing['id']]);
            echo json_encode(['ok' => true, 'xp_awarded' => 0, 'already_done' => true]);
        } else {
            $ins = $pdo->prepare(
                'INSERT INTO pupil_progress (pupil_id, level_id, lesson_id, xp_earned, answer_text) VALUES (:p, ' . BULIG_L3_LEVEL_ID . ', :l, :xp, :a)'
            );
            $ins->execute(['p' => $pupilId, 'l' => $slot, 'xp' => BULIG_L3_XP_PER_ASSESSMENT, 'a' => $answers]);
            echo json_encode(['ok' => true, 'xp_awarded' => BULIG_L3_XP_PER_ASSESSMENT, 'already_done' => false]);
        }

    } else {
        throw new InvalidArgumentException('Unknown save type.');
    }
} catch (Throwable $e) {
    // Log $e->getMessage() server-side in production.
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Could not save progress.']);
}
