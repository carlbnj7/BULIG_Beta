<?php
/**
 * BULIG - Session helpers
 * Starts a hardened session and exposes small guard helpers used by the
 * dashboards to make sure only the correct, logged-in user type can view them.
 */

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        // 'secure' => true, // enable once the app is served over HTTPS
    ]);
    session_start();
}

/** Redirects to the login page unless a pupil is logged in. */
function require_pupil_login(): void
{
    if (empty($_SESSION['user_type']) || $_SESSION['user_type'] !== 'pupil') {
        header('Location: ../index.php?type=pupil');
        exit;
    }
}

/** Redirects to the login page unless a teacher is logged in. */
function require_teacher_login(): void
{
    if (empty($_SESSION['user_type']) || $_SESSION['user_type'] !== 'teacher') {
        header('Location: ../index.php?type=teacher');
        exit;
    }
}

/** Redirects to the admin login page unless an admin is logged in. */
function require_admin_login(): void
{
    if (empty($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
        header('Location: ../admin_login.php');
        exit;
    }
}
