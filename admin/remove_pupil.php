<?php
/**
 * BULIG — Admin: remove a pupil account (system-wide, unlike the
 * teacher-side remove_pupil.php which only lets a teacher remove pupils
 * they own or unassigned ones). Progress rows in `pupil_progress` are
 * removed with it via ON DELETE CASCADE (see sql/level1_progress.sql).
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
if ($pupilId <= 0) {
    header('Location: pupils.php?error=missing');
    exit;
}

try {
    $pdo = get_db_connection();
    $del = $pdo->prepare('DELETE FROM pupils WHERE id = :id');
    $del->execute(['id' => $pupilId]);
    header('Location: pupils.php?removed=1');
    exit;
} catch (Throwable $e) {
    header('Location: pupils.php?error=server');
    exit;
}
