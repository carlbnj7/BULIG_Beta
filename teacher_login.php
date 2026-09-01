<?php
/**
 * BULIG — Teacher login handler
 * Verifies a Teacher ID + password pair against the `teachers` table and
 * starts a session for the Teacher dashboard.
 */

declare(strict_types=1);
session_start();
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?type=teacher');
    exit;
}

$teacherId = trim($_POST['teacher_id'] ?? '');
$password  = (string) ($_POST['password'] ?? '');

if ($teacherId === '' || $password === '') {
    header('Location: index.php?type=teacher&error=missing');
    exit;
}

try {
    $pdo = get_db_connection();

    $stmt = $pdo->prepare(
        'SELECT id, teacher_id, password_hash, first_name, last_name, avatar_file
         FROM teachers
         WHERE teacher_id = :teacher_id
         LIMIT 1'
    );
    $stmt->execute(['teacher_id' => $teacherId]);
    $teacher = $stmt->fetch();

    if (!$teacher || !password_verify($password, $teacher['password_hash'])) {
        header('Location: index.php?type=teacher&error=invalid');
        exit;
    }

    session_regenerate_id(true);
    $_SESSION['user_type']  = 'teacher';
    $_SESSION['teacher_pk'] = $teacher['id'];
    $_SESSION['teacher_id'] = $teacher['teacher_id'];
    $_SESSION['full_name']  = trim($teacher['first_name'] . ' ' . $teacher['last_name']);
    $_SESSION['avatar_file']= $teacher['avatar_file'];

    header('Location: teacher/dashboard.php');
    exit;

} catch (Throwable $e) {
    header('Location: index.php?type=teacher&error=server');
    exit;
}
