<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/level1_helpers.php';
require_admin_login();

$activeAdminNav = 'pupils';
$pageTitle = 'BULIG | Manage Pupils';

$roster = [];
$teachers = [];
$dbError = false;
try {
    $pdo = get_db_connection();

    $teachers = $pdo->query('SELECT id, teacher_id, first_name, last_name FROM teachers ORDER BY last_name, first_name')->fetchAll();

    $stmt = $pdo->query(
        "SELECT p.id, p.student_id, p.first_name, p.last_name, p.grade_level, p.current_level,
                p.teacher_id, t.first_name AS t_first, t.last_name AS t_last
         FROM pupils p
         LEFT JOIN teachers t ON t.id = p.teacher_id
         ORDER BY p.last_name, p.first_name"
    );
    $roster = $stmt->fetchAll();
} catch (Throwable $e) {
    $dbError = true;
}

$flashSuccess = [
    'reassigned' => '✅ Pupil reassigned.',
    'removed'    => '🗑️ Pupil account removed.',
];
$flashError = [
    'missing'    => 'Something was missing from that request.',
    'badteacher' => "That teacher doesn't exist.",
    'server'     => 'Something went wrong. Please try again.',
];
$successKey = array_key_first(array_intersect_key($_GET, $flashSuccess));
$successMsg = $successKey ? $flashSuccess[$successKey] : null;
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
            <h2 class="section-title">🧒 Manage Pupils</h2>
            <p style="color:var(--ink-soft); font-weight:700; margin-top:-8px;">Every pupil in the system, across every teacher. Reassign a pupil to a different teacher, or remove an account entirely.</p>

            <?php if ($successMsg): ?><div class="form-success"><?= htmlspecialchars($successMsg, ENT_QUOTES) ?></div><?php endif; ?>
            <?php if ($errorMsg): ?><p class="form-alert" role="alert"><?= htmlspecialchars($errorMsg, ENT_QUOTES) ?></p><?php endif; ?>

            <?php if ($dbError): ?>
                <div class="coming-soon">
                    <span class="cs-icon">⚠️</span>
                    <h1>Couldn't load pupils</h1>
                    <p>There was a problem connecting to the database. Please check the connection settings and try again.</p>
                </div>
            <?php elseif (empty($roster)): ?>
                <div class="coming-soon">
                    <span class="cs-icon">🧒</span>
                    <h1>No pupils yet</h1>
                    <p>Pupils appear here once a teacher adds them on their My Pupils page.</p>
                </div>
            <?php else: ?>
                <div class="data-card">
                    <table class="data-table">
                        <thead>
                            <tr><th>Pupil</th><th>Grade</th><th>Assigned Level</th><th>Teacher</th><th></th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($roster as $p): ?>
                            <tr>
                                <td style="font-weight:800;color:var(--ink);"><?= htmlspecialchars(trim($p['first_name'] . ' ' . $p['last_name']), ENT_QUOTES) ?><br><span style="font-weight:600;color:var(--ink-soft);font-size:11.5px;">ID <?= htmlspecialchars($p['student_id'], ENT_QUOTES) ?></span></td>
                                <td>Grade <?= (int) $p['grade_level'] ?></td>
                                <td><span class="chip chip-level"><?= htmlspecialchars(bulig_level_label((int) $p['current_level']), ENT_QUOTES) ?></span></td>
                                <td>
                                    <form action="reassign_teacher.php" method="post" class="inline-form">
                                        <input type="hidden" name="pupil_id" value="<?= (int) $p['id'] ?>">
                                        <select name="teacher_id" class="mini-select" onchange="this.form.submit()">
                                            <option value="">— Unassigned —</option>
                                            <?php foreach ($teachers as $t): ?>
                                                <option value="<?= (int) $t['id'] ?>" <?= ((int) $p['teacher_id'] === (int) $t['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars(trim($t['first_name'] . ' ' . $t['last_name']), ENT_QUOTES) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <form action="remove_pupil.php" method="post" onsubmit="return confirm('Remove this pupil account and all their progress? This can\'t be undone.');">
                                        <input type="hidden" name="pupil_id" value="<?= (int) $p['id'] ?>">
                                        <button type="submit" class="btn-mini-danger">🗑️ Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>
<script src="../js/sidebar.js"></script>
</body>
</html>
