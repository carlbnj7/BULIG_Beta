<?php
/**
 * BULIG — Pupil login handler
 * Verifies a Student ID + password pair against the `pupils` table and
 * starts a session for the Pupil dashboard.
 */

declare(strict_types=1);
session_start();
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?type=pupil');
    exit;
}

$studentId = trim($_POST['student_id'] ?? '');
$password  = (string) ($_POST['password'] ?? '');

if ($studentId === '' || $password === '') {
    header('Location: index.php?type=pupil&error=missing');
    exit;
}

// Student IDs are numbers only — reject anything else before it ever
// reaches a query (also blocks obvious junk/attack input early).
if (!ctype_digit($studentId)) {
    header('Location: index.php?type=pupil&error=invalid');
    exit;
}

try {
    $pdo = get_db_connection();

    $stmt = $pdo->prepare(
        'SELECT id, student_id, password_hash, first_name, last_name, grade_level, section, avatar_file
         FROM pupils
         WHERE student_id = :student_id
         LIMIT 1'
    );
    $stmt->execute(['student_id' => $studentId]);
    $pupil = $stmt->fetch();

    if (!$pupil || !password_verify($password, $pupil['password_hash'])) {
        // Same generic message whether the ID or the password was wrong —
        // never reveal which one, so accounts can't be enumerated.
        header('Location: index.php?type=pupil&error=invalid');
        exit;
    }

    // Success — regenerate the session ID to prevent session fixation.
    session_regenerate_id(true);
    $_SESSION['user_type']  = 'pupil';
    $_SESSION['pupil_id']   = $pupil['id'];
    $_SESSION['student_id'] = $pupil['student_id'];
    $_SESSION['full_name']  = trim($pupil['first_name'] . ' ' . $pupil['last_name']);
    $_SESSION['grade_level']= $pupil['grade_level'];
    $_SESSION['section']    = $pupil['section'];
    $_SESSION['avatar_file']= $pupil['avatar_file'];

    header('Location: pupil/dashboard.php');
    exit;

} catch (Throwable $e) {
    // Log the real error server-side in production (error_log($e->getMessage())).
    header('Location: index.php?type=pupil&error=server');
    exit;
}
