<?php
/**
 * Included by every teacher/ page. Expects $activeNav to be set beforehand
 * (one of: dashboard, pupils, materials, activities, progress, profile).
 */
$activeNav = $activeNav ?? 'dashboard';
$initials  = '';
foreach (preg_split('/\s+/', trim($_SESSION['full_name'] ?? 'Teacher')) as $part) {
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
            <span>Teacher space</span>
        </div>
    </div>

    <ul class="sidebar-nav">
        <li><a href="dashboard.php" class="<?= $activeNav === 'dashboard' ? 'is-active' : '' ?>"><span class="nav-icon">🏫</span> Dashboard</a></li>
        <li><a href="pupils.php" class="<?= $activeNav === 'pupils' ? 'is-active' : '' ?>"><span class="nav-icon">🧒</span> Pupils</a></li>
        <li><a href="materials.php" class="<?= $activeNav === 'materials' ? 'is-active' : '' ?>"><span class="nav-icon">📘</span> Learning Materials</a></li>
        <li><a href="activities.php" class="<?= $activeNav === 'activities' ? 'is-active' : '' ?>"><span class="nav-icon">🎯</span> Activities</a></li>
        <li><a href="progress.php" class="<?= $activeNav === 'progress' ? 'is-active' : '' ?>"><span class="nav-icon">📊</span> Progress</a></li>
        <li><a href="profile.php" class="<?= $activeNav === 'profile' ? 'is-active' : '' ?>"><span class="nav-icon">👤</span> Profile</a></li>
        <li><a href="../logout.php"><span class="nav-icon">🚪</span> Logout</a></li>
    </ul>

    <div class="sidebar-user">
        <div class="sidebar-user-avatar"><?= htmlspecialchars($initials ?: 'T', ENT_QUOTES) ?></div>
        <div class="sidebar-user-meta">
            <strong><?= htmlspecialchars($_SESSION['full_name'] ?? 'Teacher', ENT_QUOTES) ?></strong>
            <span><?= htmlspecialchars($_SESSION['teacher_id'] ?? '', ENT_QUOTES) ?></span>
        </div>
    </div>
</aside>
