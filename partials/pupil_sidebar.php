<?php
/**
 * Pupil sidebar — included by every page in /pupil/.
 * Expects $activePupilNav to be set by the including page (e.g. 'home').
 */
$activePupilNav = $activePupilNav ?? 'home';
$firstName = trim((string) explode(' ', (string) ($_SESSION['full_name'] ?? ''))[0]) ?: 'Reader';
$initial   = strtoupper(substr($firstName, 0, 1));

$navItems = [
    'home'         => ['dashboard.php',    '🏠', 'Home'],
    'lessons'      => ['lessons.php',      '📚', 'My Lessons'],
    'activities'   => ['activities.php',   '✏️', 'Activities'],
    'achievements' => ['achievements.php', '🏆', 'Achievements'],
    'progress'     => ['progress.php',     '📊', 'My Progress'],
    'profile'      => ['profile.php',      '👤', 'Profile'],
];
?>
<aside class="sidebar sidebar-pupil" id="sidebar">
    <div class="sidebar-brand">
        <img src="<?= $assetPath ?? '../assets/' ?>bulig-logo.png" alt="BULIG">
        <span>BULIG</span>
    </div>
    <nav class="sidebar-nav">
        <?php foreach ($navItems as $key => [$href, $icon, $label]): ?>
            <a href="<?= $href ?>" class="side-link <?= $activePupilNav === $key ? 'is-active' : '' ?>">
                <span class="side-icon"><?= $icon ?></span><?= htmlspecialchars($label, ENT_QUOTES) ?>
            </a>
        <?php endforeach; ?>
    </nav>
    <div class="sidebar-foot">
        <div class="sidebar-user">
            <div class="avatar a-pupil"><?= htmlspecialchars($initial, ENT_QUOTES) ?></div>
            <div class="sidebar-user-meta">
                <div class="user-name"><?= htmlspecialchars($_SESSION['full_name'] ?: $_SESSION['student_id'], ENT_QUOTES) ?></div>
                <div class="user-role">Grade <?= htmlspecialchars((string) ($_SESSION['grade_level'] ?? ''), ENT_QUOTES) ?></div>
            </div>
        </div>
        <a href="../logout.php" class="btn-logout">🚪 Log Out</a>
    </div>
</aside>
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>
