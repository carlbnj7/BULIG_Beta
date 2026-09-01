<?php
/**
 * BULIG — Teacher: remove a pupil.
 * Only pupils actually owned by this teacher (teacher_id = them) can be
 * removed — legacy/unassigned pupils are visible but not deletable here,
 * to avoid one teacher wiping out another's or shared demo data by mistake.
 * Deleting a pupil cascades to their pupil_progress rows automatically.
 */
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_teacher_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: pupils.php');
    exit;
}

$pupilId = (int) ($_POST['pupil_id'] ?? 0);
if ($pupilId <= 0) {
    header('Location: pupils.php?error=badid');
    exit;
}

try {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('DELETE FROM pupils WHERE id = :pid AND teacher_id = :tid');
    $stmt->execute(['pid' => $pupilId, 'tid' => (int) $_SESSION['teacher_pk']]);

    if ($stmt->rowCount() === 0) {
        header('Location: pupils.php?error=notyours');
        exit;
    }

    header('Location: pupils.php?removed=1');
    exit;
} catch (Throwable $e) {
    header('Location: pupils.php?error=server');
    exit;
}
