<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/level1_helpers.php';
require_once __DIR__ . '/../config/level2_helpers.php';
require_once __DIR__ . '/../config/level2b_helpers.php';
require_once __DIR__ . '/../config/level3_helpers.php';
require_teacher_login();

$activeTeacherNav = 'pupils';
$pageTitle = 'BULIG | My Pupils';

$teacherPk = (int) $_SESSION['teacher_pk'];

$roster  = [];
$dbError = false;
try {
    $pdo    = get_db_connection();
    $roster = bulig_level1_roster($pdo, $teacherPk);
} catch (Throwable $e) {
    $dbError = true;
}

$statusChip = [
    'not_started' => ['chip-neutral',  'Not started'],
    'in_progress' => ['chip-progress', 'In progress'],
    'completed'   => ['chip-done',     'Completed'],
    'champion'    => ['chip-champion', '🏆 Champion'],
];

$flashSuccess = [
    'added'   => '✅ Pupil added successfully.',
    'removed' => '🗑️ Pupil removed.',
    'leveled' => '🎚️ Starting level updated.',
    'graded'  => '🏫 Grade level updated — Level 4 content now matches.',
];
$flashError = [
    'missing'   => 'Please fill in every required field.',
    'badid'     => 'Student ID must contain numbers only.',
    'mismatch'  => "Passwords don't match.",
    'weak'      => 'Password must be at least 6 characters.',
    'duplicate' => 'That Student ID is already registered.',
    'notyours'  => "You can only manage pupils you've added.",
    'badlevel'  => 'Please choose a valid level.',
    'badgrade'  => 'Please choose a valid grade (1-6).',
    'server'    => 'Something went wrong on our end. Please try again.',
];

$successMsg = null;
foreach (['added', 'removed', 'leveled', 'graded'] as $key) {
    if (isset($_GET[$key])) { $successMsg = $flashSuccess[$key]; break; }
}
$errorMsg = isset($_GET['error']) ? ($flashError[$_GET['error']] ?? 'Something went wrong.') : null;
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
            <h2 class="section-title">👥 My Pupils</h2>

            <?php if ($successMsg): ?><div class="form-success"><?= htmlspecialchars($successMsg, ENT_QUOTES) ?></div><?php endif; ?>
            <?php if ($errorMsg): ?><p class="form-alert" role="alert"><?= htmlspecialchars($errorMsg, ENT_QUOTES) ?></p><?php endif; ?>

            <?php if ($dbError): ?>
                <div class="coming-soon">
                    <span class="cs-icon">⚠️</span>
                    <h1>Couldn't load the roster</h1>
                    <p>There was a problem connecting to the database. Please check the connection settings and try again.</p>
                </div>
            <?php else: ?>

                <details class="disclosure" id="addPupil">
                    <summary>➕ Add a Pupil</summary>
                    <div class="disclosure-body">
                        <form action="add_pupil.php" method="post" novalidate>
                            <div class="form-grid">
                                <label class="field">
                                    <span class="field-label">Student ID</span>
                                    <span class="field-control"><input type="text" name="student_id" inputmode="numeric" pattern="[0-9]*" placeholder="e.g. 20232230" required></span>
                                    <span class="field-hint">Numbers only</span>
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
                                    <span class="field-label">Grade Level</span>
                                    <span class="field-control">
                                        <select name="grade_level" required style="border:none;background:transparent;width:100%;padding:12px 14px;font-family:var(--font-body);font-weight:700;">
                                            <?php for ($g = 1; $g <= 6; $g++): ?>
                                                <option value="<?= $g ?>">Grade <?= $g ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </span>
                                </label>
                                <label class="field">
                                    <span class="field-label">Section (optional)</span>
                                    <span class="field-control"><input type="text" name="section" placeholder="e.g. Sampaguita"></span>
                                </label>
                                <label class="field">
                                    <span class="field-label">Starting Level</span>
                                    <span class="field-control">
                                        <select name="starting_level" required style="border:none;background:transparent;width:100%;padding:12px 14px;font-family:var(--font-body);font-weight:700;">
                                            <?php for ($lv = 1; $lv <= BULIG_MAX_LEVEL; $lv++): ?>
                                                <option value="<?= $lv ?>"><?= $lv === 1 ? bulig_level_label($lv) . ' (default)' : bulig_level_label($lv) ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </span>
                                    <span class="field-hint">Not every pupil needs to start at Level 1</span>
                                </label>
                                <label class="field">
                                    <span class="field-label">Password</span>
                                    <span class="field-control"><input type="password" name="password" required></span>
                                </label>
                                <label class="field">
                                    <span class="field-label">Confirm Password</span>
                                    <span class="field-control"><input type="password" name="confirm_password" required></span>
                                </label>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn-mini" style="padding:10px 20px;font-size:13.5px;">Add Pupil</button>
                            </div>
                        </form>
                    </div>
                </details>

                <div class="data-card">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Grade</th>
                                <th>Level</th>
                                <th>Level 1 Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($roster)): ?>
                                <tr><td colspan="6" class="table-empty">No pupils yet — add your first one above.</td></tr>
                            <?php else: foreach ($roster as $p): [$chipClass, $chipLabel] = $statusChip[$p['status']]; ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['student_id'], ENT_QUOTES) ?></td>
                                    <td>
                                        <?= htmlspecialchars($p['name'], ENT_QUOTES) ?>
                                        <?php if ($p['teacher_id'] === null): ?><br><span class="chip chip-neutral" style="margin-top:4px;">Unassigned</span><?php endif; ?>
                                    </td>
                                    <td>
                                        <form action="assign_grade.php" method="post" class="inline-form">
                                            <input type="hidden" name="pupil_id" value="<?= $p['id'] ?>">
                                            <select name="grade_level" class="mini-select">
                                                <?php for ($g = 1; $g <= 6; $g++): ?>
                                                    <option value="<?= $g ?>" <?= $g === $p['grade_level'] ? 'selected' : '' ?>>Grade <?= $g ?></option>
                                                <?php endfor; ?>
                                            </select>
                                            <button type="submit" class="btn-mini">Save</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="assign_level.php" method="post" class="inline-form">
                                            <input type="hidden" name="pupil_id" value="<?= $p['id'] ?>">
                                            <select name="level" class="mini-select">
                                                <?php for ($lv = 1; $lv <= BULIG_MAX_LEVEL; $lv++): ?>
                                                    <option value="<?= $lv ?>" <?= $lv === $p['current_level'] ? 'selected' : '' ?>><?= bulig_level_label($lv) ?></option>
                                                <?php endfor; ?>
                                            </select>
                                            <button type="submit" class="btn-mini">Save</button>
                                        </form>
                                    </td>
                                    <td>
                                        <span class="chip <?= $chipClass ?>"><?= $chipLabel ?></span>
                                        <?php if ($p['current_level'] === 1): ?>
                                            <div style="font-size:11px;color:var(--ink-soft);margin-top:3px;"><?= count($p['completed']) ?>/<?= $p['lessonsTotal'] ?> quests · <?= $p['xp'] ?> XP</div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($p['teacher_id'] === $teacherPk): ?>
                                        <form action="remove_pupil.php" method="post" onsubmit="return confirm('Remove this pupil? This cannot be undone.');">
                                            <input type="hidden" name="pupil_id" value="<?= $p['id'] ?>">
                                            <button type="submit" class="btn-mini btn-mini-danger">Remove</button>
                                        </form>
                                        <?php else: ?>
                                            <span class="btn-mini btn-mini-ghost" style="cursor:default;">Legacy</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
                <p style="color:var(--ink-soft); font-weight:700; font-size:13px; margin-top:14px;">
                    "Unassigned" pupils were added before teacher accounts existed — anyone can still update their level, but only the teacher who added a pupil can remove them.
                </p>
                <p style="color:var(--ink-soft); font-weight:700; font-size:13px; margin-top:4px;">
                    🏫 Grade controls Level 4 (Fluency) content — a pupil only ever sees the passages for the grade set here, so keep it current as pupils move up a grade.
                </p>

            <?php endif; ?>
        </main>
    </div>
</div>
<script src="../js/sidebar.js"></script>
</body>
</html>
