<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/level1_helpers.php';
require_once __DIR__ . '/../config/level2_helpers.php';
require_once __DIR__ . '/../config/level2b_helpers.php';
require_once __DIR__ . '/../config/level3_helpers.php';
require_once __DIR__ . '/../config/level4_helpers.php';
require_once __DIR__ . '/../config/level4_content.php';
require_teacher_login();

$activeTeacherNav = 'materials';
$pageTitle = 'BULIG | Learning Materials';

$l4Content = bulig_level4_content();
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
            <h2 class="section-title">📚 Learning Materials</h2>
            <p style="color:var(--ink-soft); font-weight:700; margin-top:-8px;">A quick reference for what's inside each BULIG level — handy when you're deciding a pupil's starting level or grade.</p>

            <div class="materials-grid">
                <div class="material-card">
                    <div class="material-head"><span>🚀</span><h3>Level 1 — Oral Language</h3></div>
                    <p><?= BULIG_L1_LESSON_COUNT ?> lessons covering self-introductions, following directions, describing pictures, storytelling, poems, and tongue twisters.</p>
                    <ul class="material-list">
                        <li>Meet &amp; Greet, Mission Trail, Polite Missions</li>
                        <li>Word Toolkit, Picture Chat, Describe &amp; Draw</li>
                        <li>Story Chain, Picture Talk, Recite &amp; Shine</li>
                        <li>Talk/Play/Share, Word Friends, Tongue Twisters</li>
                    </ul>
                </div>

                <div class="material-card">
                    <div class="material-head"><span>🔤</span><h3>Level 2A — Phonological Awareness</h3></div>
                    <p><?= BULIG_L2_LESSON_COUNT ?> lessons building sound awareness: isolating, identifying, blending, segmenting, deleting, adding, and swapping sounds in spoken words.</p>
                </div>

                <div class="material-card">
                    <div class="material-head"><span>🔡</span><h3>Level 2B — Phonological Awareness II</h3></div>
                    <p><?= BULIG_L2B_LESSON_COUNT ?> units across 6 skills: blending &amp; segmenting onsets/rimes, syllables, sentence segmentation, and rhymes.</p>
                </div>

                <div class="material-card">
                    <div class="material-head"><span>📖</span><h3>Level 3 — Word Recognition</h3></div>
                    <p><?= BULIG_L3_LESSON_COUNT ?> lessons: short-vowel CVC word families, vowel teams, common blends/digraphs, and 5 sight-word sets.</p>
                </div>

                <div class="material-card material-card-wide">
                    <div class="material-head"><span>🎙️</span><h3>Level 4 — Fluency (Grade-specific)</h3></div>
                    <p>Reading-fluency passages, one grade-appropriate set per grade level. A pupil only ever sees their own grade's set. Pre-/Post-test scores are entered by you on the Progress page.</p>
                    <div class="material-grade-table">
                        <?php foreach ($l4Content as $grade => $c): ?>
                        <div class="material-grade-row">
                            <span class="chip chip-level">Grade <?= $grade ?></span>
                            <span><strong><?= count($c['interventions']) ?></strong> practice passages</span>
                            <span style="color:var(--ink-soft);">Pre/Post: "<?= htmlspecialchars($c['pretest']['title'], ENT_QUOTES) ?>" (<?= $c['pretest']['word_count'] ?> words)</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <h2 class="section-title" style="margin-top:28px;">🧭 How pupils move between levels</h2>
            <div class="material-card material-card-wide">
                <p>A level unlocks for a pupil the normal way — by finishing every lesson in the level before it — <strong>or</strong> the moment you assign them to it (or a higher one) on <a href="pupils.php">My Pupils</a>. That's handy for a fluent pupil who doesn't need to start at Level 1. Level 4 additionally requires a Grade to be set, since its content is grade-specific.</p>
            </div>
        </main>
    </div>
</div>
<script src="../js/sidebar.js"></script>
</body>
</html>
