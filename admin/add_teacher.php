<?php
/**
 * BULIG — Admin: create a teacher account.
 * Until now there was no way to create a teacher account at all except
 * direct DB access (teacher_register.php is intentionally disabled —
 * self sign-up isn't allowed). This is that missing piece.
 */
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_admin_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: teachers.php');
    exit;
}

$teacherId = trim((string) ($_POST['teacher_id'] ?? ''));
$password  = (string) ($_POST['password'] ?? '');
$firstName = trim((string) ($_POST['first_name'] ?? ''));
$lastName  = trim((string) ($_POST['last_name'] ?? ''));

if ($teacherId === '' || $password === '' || $firstName === '' || $lastName === '') {
    header('Location: teachers.php?error=missing');
    exit;
}
if (strlen($password) < 6) {
    header('Location: teachers.php?error=shortpass');
    exit;
}

try {
    $pdo = get_db_connection();

    $check = $pdo->prepare('SELECT id FROM teachers WHERE teacher_id = :tid');
    $check->execute(['tid' => $teacherId]);
    if ($check->fetch()) {
        header('Location: teachers.php?error=duplicate');
        exit;
    }

    $ins = $pdo->prepare(
        'INSERT INTO teachers (teacher_id, password_hash, first_name, last_name)
         VALUES (:tid, :pw, :fn, :ln)'
    );
    $ins->execute([
        'tid' => $teacherId,
        'pw'  => password_hash($password, PASSWORD_DEFAULT),
        'fn'  => $firstName,
        'ln'  => $lastName,
    ]);

    header('Location: teachers.php?added=1');
    exit;
} catch (Throwable $e) {
    header('Location: teachers.php?error=server');
    exit;
}
