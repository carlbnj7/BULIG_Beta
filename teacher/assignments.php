<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/level1_helpers.php';
require_once __DIR__ . '/../config/level4_helpers.php';
require_teacher_login();

$activeTeacherNav = 'assignments';
$pageTitle = 'BULIG | Assignments';

$teacherPk = (int) $_SESSION['teacher_pk'];
$roster = [];
$dbError = false;
try {
    $pdo    = get_db_connection();
    $roster = bulig_level1_roster($pdo, $teacherPk);
} catch (Throwable $e) {
    $dbError = true;
}

// Group by assigned level for the summary strip at the top.
$byLevel = [];
foreach ($roster as $p) {
    $byLevel[$p['current_level']] = ($byLevel[$p['current_level']] ?? 0) + 1;
}
ksort($byLevel);

// Sort the table itself by assigned level (highest first — easiest to
// spot who's been placed ahead) then name.
usort($roster, fn($a, $b) => $b['current_level'] <=> $a['current_level'] ?: strcmp($a['name'], $b['name']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include __DIR__ . '/../partials/head_dash.php'; ?>
<link rel="stylesheet" href="../css/manage.css">
</head>
<body class="bg-teacher">
<div class="app-shell">
    <?php include __DIR__ . '/../partials/teacher_sidebar.php'; ?>
    <div class="content-wrap">
        <?php include __DIR__ . '/../partials/mobile_topbar.php'; ?>
        <main class="dash-main">
            <h2 class="section-title">📋 Assignments</h2>
            <p style="color:var(--ink-soft); font-weight:700; margin-top:-8px;">A quick overview of every pupil's assigned level and grade. To change one, use the dropdowns on <a href="pupils.php">My Pupils</a> — updates show up here immediately.</p>

            <?php if ($dbError): ?>
                <div class="coming-soon">
                    <span class="cs-icon">⚠️</span>
                    <h1>Couldn't load assignments</h1>
                    <p>There was a problem connecting to the database. Please check the connection settings and try again.</p>
                </div>
            <?php elseif (empty($roster)): ?>
                <div class="coming-soon">
                    <span class="cs-icon">👥</span>
                    <h1>No pupils yet</h1>
                    <p>Add your first pupil on <a href="pupils.php">My Pupils</a> to start assigning levels and grades.</p>
                </div>
            <?php else: ?>
                <div class="stat-grid" style="grid-template-columns: repeat(<?= min(5, count($byLevel)) ?>, 1fr);">
                    <?php foreach ($byLevel as $lvl => $count): ?>
                    <div class="stat-card">
                        <span class="stat-icon">🎯</span>
                        <div class="stat-value"><?= $count ?></div>
                        <div class="stat-label"><?= htmlspecialchars(bulig_level_label($lvl), ENT_QUOTES) ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="data-card" style="margin-top:20px;">
                    <table class="data-table">
                        <thead>
                            <tr><th>Pupil</th><th>Assigned Level</th><th>Grade</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($roster as $p): ?>
                            <tr>
                                <td style="font-weight:800;color:var(--ink);"><?= htmlspecialchars($p['name'], ENT_QUOTES) ?><br><span style="font-weight:600;color:var(--ink-soft);font-size:11.5px;">ID <?= htmlspecialchars($p['student_id'], ENT_QUOTES) ?></span></td>
                                <td><span class="chip chip-level"><?= htmlspecialchars(bulig_level_label($p['current_level']), ENT_QUOTES) ?></span></td>
                                <td><?= ($p['grade_level'] >= 1 && $p['grade_level'] <= 6) ? 'Grade ' . $p['grade_level'] : '<span class="chip chip-neutral">Not set</span>' ?></td>
                                <td><?= count($p['completed']) ?>/<?= BULIG_L1_LESSON_COUNT ?> Level 1 lessons done</td>
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
