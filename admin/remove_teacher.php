<?php
/**
 * BULIG — Admin: remove a teacher account. Their pupils are NOT deleted —
 * `fk_pupil_teacher` is ON DELETE SET NULL, so those pupils just become
 * unassigned/legacy (visible to every teacher, exactly like pupils added
 * before the ownership feature existed) rather than orphaned.
 */
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_admin_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: teachers.php');
    exit;
}

$teacherId = (int) ($_POST['teacher_id'] ?? 0);
if ($teacherId <= 0) {
    header('Location: teachers.php?error=missing');
    exit;
}

try {
    $pdo = get_db_connection();
    $del = $pdo->prepare('DELETE FROM teachers WHERE id = :id');
    $del->execute(['id' => $teacherId]);
    header('Location: teachers.php?removed=1');
    exit;
} catch (Throwable $e) {
    header('Location: teachers.php?error=server');
    exit;
}
