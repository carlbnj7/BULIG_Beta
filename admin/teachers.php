<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_admin_login();

$activeAdminNav = 'teachers';
$pageTitle = 'BULIG | Manage Teachers';

$roster = [];
$dbError = false;
try {
    $pdo = get_db_connection();
    $stmt = $pdo->query(
        "SELECT t.id, t.teacher_id, t.first_name, t.last_name, t.created_at,
                (SELECT COUNT(*) FROM pupils p WHERE p.teacher_id = t.id) AS pupil_count
         FROM teachers t
         ORDER BY t.last_name, t.first_name"
    );
    $roster = $stmt->fetchAll();
} catch (Throwable $e) {
    $dbError = true;
}

$flashSuccess = [
    'added'   => '✅ Teacher account created.',
    'removed' => '🗑️ Teacher account removed. Their pupils are now unassigned/legacy, not deleted.',
];
$flashError = [
    'missing'   => 'Please fill in every field.',
    'shortpass' => 'Password must be at least 6 characters.',
    'duplicate' => 'That Teacher ID is already in use.',
    'server'    => 'Something went wrong. Please try again.',
];
$successMsg = isset($_GET['added']) ? $flashSuccess['added'] : (isset($_GET['removed']) ? $flashSuccess['removed'] : null);
$errorMsg = isset($_GET['error']) ? ($flashError[$_GET['error']] ?? 'Something went wrong.') : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include __DIR__ . '/../partials/head_dash.php'; ?>
<link rel="stylesheet" href="../css/manage.css">
</head>
<body class="bg-admin">
<div class="app-shell">
    <?php include __DIR__ . '/../partials/admin_sidebar.php'; ?>
    <div class="content-wrap">
        <?php include __DIR__ . '/../partials/mobile_topbar.php'; ?>
        <main class="dash-main">
            <h2 class="section-title">🍎 Manage Teachers</h2>

            <?php if ($successMsg): ?><div class="form-success"><?= htmlspecialchars($successMsg, ENT_QUOTES) ?></div><?php endif; ?>
            <?php if ($errorMsg): ?><p class="form-alert" role="alert"><?= htmlspecialchars($errorMsg, ENT_QUOTES) ?></p><?php endif; ?>

            <?php if ($dbError): ?>
                <div class="coming-soon">
                    <span class="cs-icon">⚠️</span>
                    <h1>Couldn't load teachers</h1>
                    <p>There was a problem connecting to the database. Please check the connection settings and try again.</p>
                </div>
            <?php else: ?>

                <details class="disclosure" id="addTeacher">
                    <summary>➕ Add a Teacher</summary>
                    <div class="disclosure-body">
                        <form action="add_teacher.php" method="post" novalidate>
                            <div class="form-grid">
                                <label class="field">
                                    <span class="field-label">Teacher ID</span>
                                    <span class="field-control"><input type="text" name="teacher_id" placeholder="e.g. T2026001" required></span>
                                    <span class="field-hint">Used to log in — must be unique</span>
                                </label>
                                <label class="field">
                                    <span class="field-label">First Name</span>
                                    <span class="field-control"><input type="text" name="first_name" required></span>
                                </label>
                                <label class="field">
                                    <span class="field-label">Last Name</span>
                                    <span class="field-control"><input type="text" name="last_name" required></span>
                                </label>
                                <label class="field">
                                    <span class="field-label">Temporary Password</span>
                                    <span class="field-control"><input type="text" name="password" minlength="6" required></span>
                                    <span class="field-hint">At least 6 characters — share this with the teacher</span>
                                </label>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn-mini">➕ Create Account</button>
                            </div>
                        </form>
                    </div>
                </details>

                <?php if (empty($roster)): ?>
                <div class="coming-soon">
                    <span class="cs-icon">🍎</span>
                    <h1>No teachers yet</h1>
                    <p>Add the first teacher account above to get started.</p>
                </div>
                <?php else: ?>
                <div class="data-card">
                    <table class="data-table">
                        <thead>
                            <tr><th>Teacher</th><th>Teacher ID</th><th>Pupils</th><th>Joined</th><th></th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($roster as $t): ?>
                            <tr>
                                <td style="font-weight:800;color:var(--ink);"><?= htmlspecialchars(trim($t['first_name'] . ' ' . $t['last_name']), ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($t['teacher_id'], ENT_QUOTES) ?></td>
                                <td><span class="chip chip-level"><?= (int) $t['pupil_count'] ?> pupil<?= ((int) $t['pupil_count']) === 1 ? '' : 's' ?></span></td>
                                <td><?= htmlspecialchars(date('M j, Y', strtotime((string) $t['created_at'])), ENT_QUOTES) ?></td>
                                <td>
                                    <form action="remove_teacher.php" method="post" onsubmit="return confirm('Remove this teacher? Their pupils will become unassigned, not deleted.');">
                                        <input type="hidden" name="teacher_id" value="<?= (int) $t['id'] ?>">
                                        <button type="submit" class="btn-mini-danger">🗑️ Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </main>
    </div>
</div>
<script src="../js/sidebar.js"></script>
</body>
</html>
