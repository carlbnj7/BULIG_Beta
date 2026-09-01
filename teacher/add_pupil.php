<?php
/**
 * BULIG — Teacher: add a pupil.
 * Creates a new pupil owned by the logged-in teacher, with a chosen
 * starting reading level. Handler only — redirects back to pupils.php.
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

$studentId   = trim($_POST['student_id'] ?? '');
$password    = (string) ($_POST['password'] ?? '');
$confirm     = (string) ($_POST['confirm_password'] ?? '');
$firstName   = trim($_POST['first_name'] ?? '');
$lastName    = trim($_POST['last_name'] ?? '');
$gradeLevel  = (int) ($_POST['grade_level'] ?? 0);
$section     = trim($_POST['section'] ?? '');
$startLevel  = (int) ($_POST['starting_level'] ?? 1);

if ($startLevel < 1 || $startLevel > BULIG_MAX_LEVEL) {
    $startLevel = 1;
}

if ($studentId === '' || $password === '' || $firstName === '' || $lastName === '' || $gradeLevel < 1 || $gradeLevel > 6) {
    header('Location: pupils.php?error=missing');
    exit;
}
if (!ctype_digit($studentId)) {
    header('Location: pupils.php?error=badid');
    exit;
}
if ($password !== $confirm) {
    header('Location: pupils.php?error=mismatch');
    exit;
}
if (strlen($password) < 6) {
    header('Location: pupils.php?error=weak');
    exit;
}

try {
    $pdo = get_db_connection();

    $check = $pdo->prepare('SELECT id FROM pupils WHERE student_id = :sid');
    $check->execute(['sid' => $studentId]);
    if ($check->fetch()) {
        header('Location: pupils.php?error=duplicate');
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $ins = $pdo->prepare(
        'INSERT INTO pupils (teacher_id, student_id, password_hash, first_name, last_name, grade_level, section, current_level)
         VALUES (:tid, :sid, :hash, :fn, :ln, :grade, :section, :level)'
    );
    $ins->execute([
        'tid'     => (int) $_SESSION['teacher_pk'],
        'sid'     => $studentId,
        'hash'    => $hash,
        'fn'      => $firstName,
        'ln'      => $lastName,
        'grade'   => $gradeLevel,
        'section' => $section !== '' ? $section : null,
        'level'   => $startLevel,
    ]);

    header('Location: pupils.php?added=1');
    exit;
} catch (Throwable $e) {
    header('Location: pupils.php?error=server');
    exit;
}
