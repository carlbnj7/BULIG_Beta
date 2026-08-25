<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_pupil_login();
$activePupilNav = 'achievements';
$pageTitle = 'BULIG | Achievements';

// No badges exist yet since Level 1 activities aren't recorded — shown as
// a locked/earn-able grid rather than faked "earned" badges.
$badges = [
    ['icon' => '🌟', 'name' => 'First Steps'],
    ['icon' => '🗣️', 'name' => 'Great Talker'],
    ['icon' => '🎭', 'name' => 'Storyteller'],
    ['icon' => '🎨', 'name' => 'Describer'],
    ['icon' => '🎤', 'name' => 'Poem Star'],
    ['icon' => '🏆', 'name' => 'Level 1 Champ'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include __DIR__ . '/../partials/head_dash.php'; ?>
</head>
<body class="bg-pupil">
<div class="deco-layer" aria-hidden="true">
    <span class="deco d-star" style="top:10%; right:14%;">✦</span>
</div>
<div class="app-shell">
    <?php include __DIR__ . '/../partials/pupil_sidebar.php'; ?>
    <div class="content-wrap">
        <?php include __DIR__ . '/../partials/mobile_topbar.php'; ?>
        <main class="dash-main">
            <h2 class="section-title">🏆 Achievements</h2>
            <p style="color:var(--ink-soft); font-weight:700; margin-top:-6px;">Complete activities in Level 1 to unlock these badges.</p>
            <div class="badge-grid" style="margin-top:16px;">
                <?php foreach ($badges as $b): ?>
                <div class="badge-slot">
                    <span class="badge-icon"><?= $b['icon'] ?></span>
                    <div class="badge-name"><?= htmlspecialchars($b['name'], ENT_QUOTES) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</div>
<script src="../js/sidebar.js"></script>
</body>
</html>
