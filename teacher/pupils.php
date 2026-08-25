<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_teacher_login();

$activeTeacherNav = 'pupils';
$pageTitle = 'BULIG | My Pupils';

$pupils = [];
$dbError = false;
try {
    $pdo = get_db_connection();
    $stmt = $pdo->query(
        'SELECT student_id, first_name, last_name, grade_level, section, created_at
         FROM pupils
         ORDER BY last_name, first_name'
    );
    $pupils = $stmt->fetchAll();
} catch (Throwable $e) {
    $dbError = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include __DIR__ . '/../partials/head_dash.php'; ?>
</head>
<body class="bg-teacher">
<div class="app-shell">
    <?php include __DIR__ . '/../partials/teacher_sidebar.php'; ?>
    <div class="content-wrap">
        <?php include __DIR__ . '/../partials/mobile_topbar.php'; ?>
        <main class="dash-main">
            <h2 class="section-title">👥 My Pupils</h2>

            <?php if ($dbError): ?>
                <div class="coming-soon">
                    <span class="cs-icon">⚠️</span>
                    <h1>Couldn't load the roster</h1>
                    <p>There was a problem connecting to the database. Please check the connection settings and try again.</p>
                </div>
            <?php else: ?>
                <div class="data-card">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Grade</th>
                                <th>Section</th>
                                <th>Level 1 Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pupils)): ?>
                                <tr><td colspan="5" class="table-empty">No pupils are registered yet.</td></tr>
                            <?php else: foreach ($pupils as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['student_id'], ENT_QUOTES) ?></td>
                                    <td><?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name'], ENT_QUOTES) ?></td>
                                    <td>Grade <?= htmlspecialchars((string) $p['grade_level'], ENT_QUOTES) ?></td>
                                    <td><?= htmlspecialchars($p['section'] ?: '—', ENT_QUOTES) ?></td>
                                    <td><span class="chip chip-neutral">Not started</span></td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
                <p style="color:var(--ink-soft); font-weight:700; font-size:13px;">
                    "Level 1 Status" will show real progress once pupils begin completing lessons.
                </p>
            <?php endif; ?>
        </main>
    </div>
</div>
<script src="../js/sidebar.js"></script>
</body>
</html>
