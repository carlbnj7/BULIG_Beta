<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/level1_helpers.php';
require_pupil_login();
$activePupilNav = 'achievements';
$pageTitle = 'BULIG | Achievements';

// One real badge per Level 1 quest, marked earned once it's actually
// been completed in `pupil_progress`. A bonus badge appears once all
// 12 are done.
$badgeCatalog = [
    ['icon' => '🙋', 'name' => 'Meet & Greet'],
    ['icon' => '🕵️', 'name' => 'Mission Trail'],
    ['icon' => '🤝', 'name' => 'Polite Missions'],
    ['icon' => '🎨', 'name' => 'Word Toolkit'],
    ['icon' => '💬', 'name' => 'Picture Chat'],
    ['icon' => '🖍️', 'name' => 'Describe & Draw'],
    ['icon' => '🔗', 'name' => 'Story Chain'],
    ['icon' => '🖼️', 'name' => 'Picture Talk'],
    ['icon' => '⭐', 'name' => 'Recite & Shine'],
    ['icon' => '🎈', 'name' => 'Talk, Play & Share'],
    ['icon' => '🔤', 'name' => 'Word Friends'],
    ['icon' => '👅', 'name' => 'Tongue Twisters'],
];

try {
    $pdo     = get_db_connection();
    $summary = bulig_level1_summary($pdo, (int) $_SESSION['pupil_id']);
} catch (Throwable $e) {
    $summary = ['completed' => []];
}
$earnedSet = array_flip($summary['completed']);

$badges = [];
foreach ($badgeCatalog as $i => $b) {
    $lessonId = $i + 1;
    $badges[] = ['icon' => $b['icon'], 'name' => $b['name'], 'earned' => isset($earnedSet[$lessonId])];
}
$allEarned = count($summary['completed']) === count($badgeCatalog);
$badges[] = ['icon' => '🏆', 'name' => 'Level 1 Champ', 'earned' => $allEarned];
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
                <div class="badge-slot<?= $b['earned'] ? ' is-earned' : '' ?>">
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
