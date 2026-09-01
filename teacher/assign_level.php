<?php
/**
 * BULIG — Teacher: assign a pupil's starting/current reading level.
 * A teacher may adjust level for a pupil they own, or for any legacy/
 * unassigned pupil (teacher_id IS NULL). Handler only.
 */
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/level1_helpers.php';
require_once __DIR__ . '/../config/level2_helpers.php';
require_once __DIR__ . '/../config/level2b_helpers.php';
require_once __DIR__ . '/../config/level3_helpers.php';
require_teacher_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: pupils.php');
    exit;
}

$pupilId = (int) ($_POST['pupil_id'] ?? 0);
$level   = (int) ($_POST['level'] ?? 0);

if ($pupilId <= 0 || $level < 1 || $level > BULIG_MAX_LEVEL) {
    header('Location: pupils.php?error=badlevel');
    exit;
}

try {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare(
        'UPDATE pupils SET current_level = :level
         WHERE id = :pid AND (teacher_id = :tid OR teacher_id IS NULL)'
    );
    $stmt->execute([
        'level' => $level,
        'pid'   => $pupilId,
        'tid'   => (int) $_SESSION['teacher_pk'],
    ]);

    if ($stmt->rowCount() === 0) {
        header('Location: pupils.php?error=notyours');
        exit;
    }

    header('Location: pupils.php?leveled=1');
    exit;
} catch (Throwable $e) {
    header('Location: pupils.php?error=server');
    exit;
}
