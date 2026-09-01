<?php
/**
 * BULIG — Admin: reassign a pupil to a different teacher (or unassign
 * them back to legacy/no-teacher). This is an admin-only action —
 * teachers can only manage pupils they already own or unassigned ones,
 * they can't hand a pupil to a different teacher themselves.
 */
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_admin_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: pupils.php');
    exit;
}

$pupilId = (int) ($_POST['pupil_id'] ?? 0);
$teacherRaw = trim((string) ($_POST['teacher_id'] ?? ''));
$newTeacherId = $teacherRaw === '' ? null : (int) $teacherRaw;

if ($pupilId <= 0) {
    header('Location: pupils.php?error=missing');
    exit;
}

try {
    $pdo = get_db_connection();

    if ($newTeacherId !== null) {
        $check = $pdo->prepare('SELECT id FROM teachers WHERE id = :id');
        $check->execute(['id' => $newTeacherId]);
        if (!$check->fetch()) {
            header('Location: pupils.php?error=badteacher');
            exit;
        }
    }

    $upd = $pdo->prepare('UPDATE pupils SET teacher_id = :tid WHERE id = :pid');
    $upd->execute(['tid' => $newTeacherId, 'pid' => $pupilId]);

    header('Location: pupils.php?reassigned=1');
    exit;
} catch (Throwable $e) {
    header('Location: pupils.php?error=server');
    exit;
}
