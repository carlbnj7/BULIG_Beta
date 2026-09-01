<?php
/**
 * BULIG — Teacher: assign/change a pupil's grade level (Grade 1-6).
 * This is what gates a pupil's Level 4 (Fluency) content — Level 4 has
 * separate, grade-specific passages, and a pupil only ever sees the
 * grade that matches this column. Mirrors assign_level.php exactly:
 * a teacher may adjust the grade for a pupil they own, or for any
 * legacy/unassigned pupil (teacher_id IS NULL). Handler only.
 */
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/level1_helpers.php';
require_teacher_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: pupils.php');
    exit;
}

$pupilId = (int) ($_POST['pupil_id'] ?? 0);
$grade   = (int) ($_POST['grade_level'] ?? 0);

if ($pupilId <= 0 || $grade < 1 || $grade > 6) {
    header('Location: pupils.php?error=badgrade');
    exit;
}

try {
    $pdo  = get_db_connection();
    $stmt = $pdo->prepare(
        'UPDATE pupils SET grade_level = :grade
         WHERE id = :pid AND (teacher_id = :tid OR teacher_id IS NULL)'
    );
    $stmt->execute([
        'grade' => $grade,
        'pid'   => $pupilId,
        'tid'   => (int) $_SESSION['teacher_pk'],
    ]);

    if ($stmt->rowCount() === 0) {
        header('Location: pupils.php?error=notyours');
        exit;
    }

    header('Location: pupils.php?graded=1');
    exit;
} catch (Throwable $e) {
    header('Location: pupils.php?error=server');
    exit;
}
