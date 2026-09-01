<?php
/**
 * BULIG — Pupil profile picture upload handler.
 * See config/avatar_helpers.php for the actual validation/save logic.
 */
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/avatar_helpers.php';
require_pupil_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: profile.php');
    exit;
}

$pupilId = (int) $_SESSION['pupil_id'];

try {
    $pdo = get_db_connection();

    $newFile = bulig_save_avatar_upload($_FILES['avatar'] ?? [], 'pupil', $pupilId);

    $old = $pdo->prepare('SELECT avatar_file FROM pupils WHERE id = :id');
    $old->execute(['id' => $pupilId]);
    $oldFile = $old->fetchColumn();

    $upd = $pdo->prepare('UPDATE pupils SET avatar_file = :f WHERE id = :id');
    $upd->execute(['f' => $newFile, 'id' => $pupilId]);

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
