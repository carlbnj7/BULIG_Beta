<?php
/**
 * Included by every pupil/ page. Expects $activeNav to be set beforehand
 * (one of: home, lessons, activities, achievements, progress, profile).
 */
$activeNav = $activeNav ?? 'home';
$initials  = '';
foreach (preg_split('/\s+/', trim($_SESSION['full_name'] ?? 'Pupil')) as $part) {
    if ($part !== '') { $initials .= mb_strtoupper(mb_substr($part, 0, 1)); }
    if (mb_strlen($initials) >= 2) break;
}
?>
<div class="sidebar-scrim"></div>
<aside class="sidebar">
    <div class="sidebar-brand">
        <img src="../assets/bulig-logo.png" alt="BULIG logo">
        <div class="sidebar-brand-text">
            <strong>BULIG</strong>
            <span>Pupil space</span>
        </div>
    </div>

    <ul class="sidebar-nav">
        <li><a href="dashboard.php" class="<?= $activeNav === 'home' ? 'is-active' : '' ?>"><span class="nav-icon">🏠</span> Home</a></li>
        <li><a href="level1.php" class="<?= $activeNav === 'lessons' ? 'is-active' : '' ?>"><span class="nav-icon">📚</span> Lessons</a></li>
        <li><a href="activities.php" class="<?= $activeNav === 'activities' ? 'is-active' : '' ?>"><span class="nav-icon">🎯</span> Activities</a></li>
        <li><a href="achievements.php" class="<?= $activeNav === 'achievements' ? 'is-active' : '' ?>"><span class="nav-icon">🏆</span> Achievements</a></li>
        <li><a href="progress.php" class="<?= $activeNav === 'progress' ? 'is-active' : '' ?>"><span class="nav-icon">📈</span> Progress</a></li>
        <li><a href="profile.php" class="<?= $activeNav === 'profile' ? 'is-active' : '' ?>"><span class="nav-icon">👤</span> Profile</a></li>
        <li><a href="../logout.php"><span class="nav-icon">🚪</span> Logout</a></li>
    </ul>

    <div class="sidebar-user">
        <div class="sidebar-user-avatar"><?= htmlspecialchars($initials ?: 'P', ENT_QUOTES) ?></div>
        <div class="sidebar-user-meta">
            <strong><?= htmlspecialchars($_SESSION['full_name'] ?? 'Pupil', ENT_QUOTES) ?></strong>
            <span><?= htmlspecialchars(($_SESSION['grade_level'] ?? '') . ' · ' . ($_SESSION['section'] ?? ''), ENT_QUOTES) ?></span>
        </div>
    </div>
</aside>
