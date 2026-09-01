<?php
/**
 * BULIG — Admin profile picture upload handler.
 * See config/avatar_helpers.php for the actual validation/save logic.
 */
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/avatar_helpers.php';
require_admin_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: profile.php');
    exit;
}

$adminId = (int) $_SESSION['admin_pk'];

try {
    $pdo = get_db_connection();

    $newFile = bulig_save_avatar_upload($_FILES['avatar'] ?? [], 'admin', $adminId);

    $old = $pdo->prepare('SELECT avatar_file FROM admins WHERE id = :id');
    $old->execute(['id' => $adminId]);
    $oldFile = $old->fetchColumn();

    $upd = $pdo->prepare('UPDATE admins SET avatar_file = :f WHERE id = :id');
    $upd->execute(['f' => $newFile, 'id' => $adminId]);

    bulig_delete_avatar_file($oldFile ?: null);
    $_SESSION['avatar_file'] = $newFile;

    header('Location: profile.php?avatar=1');
    exit;
} catch (RuntimeException $e) {
    header('Location: profile.php?avatar_error=' . urlencode($e->getMessage()));
    exit;
} catch (Throwable $e) {
    header('Location: profile.php?avatar_error=' . urlencode('Something went wrong. Please try again.'));
    exit;
}
