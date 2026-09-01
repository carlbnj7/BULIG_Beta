<?php
/**
 * Admin sidebar — included by every page in /admin/.
 * Expects $activeAdminNav to be set by the including page (e.g. 'dashboard').
 */
$activeAdminNav = $activeAdminNav ?? 'dashboard';
$firstName = trim((string) explode(' ', (string) ($_SESSION['full_name'] ?? ''))[0]) ?: 'Admin';
$initial   = strtoupper(substr($firstName, 0, 1));

$navItems = [
    'dashboard' => ['dashboard.php', '🏠', 'Dashboard'],
    'teachers'  => ['teachers.php',  '🍎', 'Teachers'],
    'pupils'    => ['pupils.php',    '🧒', 'Pupils'],
    'profile'   => ['profile.php',   '👤', 'Profile'],
];
?>
<aside class="sidebar sidebar-admin" id="sidebar">
    <div class="sidebar-brand">
        <img src="../assets/bulig-logo.png" alt="BULIG">
        <span>BULIG</span>
    </div>
    <nav class="sidebar-nav">
        <?php foreach ($navItems as $key => [$href, $icon, $label]): ?>
            <a href="<?= $href ?>" class="side-link <?= $activeAdminNav === $key ? 'is-active' : '' ?>">
                <span class="side-icon"><?= $icon ?></span><?= htmlspecialchars($label, ENT_QUOTES) ?>
            </a>
        <?php endforeach; ?>
    </nav>
    <div class="sidebar-foot">
        <div class="sidebar-user">
            <?php
            require_once __DIR__ . '/../config/avatar_helpers.php';
            echo bulig_avatar_html($_SESSION['avatar_file'] ?? null, 'a-admin', $initial, '../');
            ?>
            <div class="sidebar-user-meta">
                <div class="user-name"><?= htmlspecialchars($_SESSION['full_name'] ?: $_SESSION['admin_id'], ENT_QUOTES) ?></div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
        <a href="../logout.php" class="btn-logout">🚪 Log Out</a>
    </div>
</aside>
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>
