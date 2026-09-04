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
require_once __DIR__ . '/../config/level5_helpers.php';
require_once __DIR__ . '/../config/level5_content.php';
require_teacher_login();

$activeTeacherNav = 'progress';
$pageTitle = 'BULIG | Progress';

$teacherPk = (int) $_SESSION['teacher_pk'];

$roster  = [];
$dbError = false;
try {
    $pdo    = get_db_connection();
    $roster = bulig_level1_roster($pdo, $teacherPk);

    // Attach Level 4 fluency data (grade, summary, pretest/posttest
    // passage) to every roster entry that's eligible for it, so the
    // scoring section below doesn't need a second DB round trip.
    $L4_CONTENT = bulig_level4_content();
    foreach ($roster as $i => $p) {
        $eligible = bulig_level3_is_complete($pdo, $p['id']);
        $grade    = bulig_pupil_grade($pdo, $p['id']);
        $roster[$i]['l4_eligible'] = $eligible;
        $roster[$i]['l4_grade']    = $grade;
        if ($eligible && $grade) {
            $roster[$i]['l4_summary'] = bulig_level4_summary($pdo, $p['id'], $grade);
            $roster[$i]['l4_content'] = $L4_CONTENT[$grade] ?? null;
        } else {
            $roster[$i]['l4_summary'] = null;
            $roster[$i]['l4_content'] = null;
        }

        // Level 5 — pupil-scored, so this is monitoring-only (no form).
        $l5Eligible = bulig_level4_is_complete($pdo, $p['id'], $grade);
        $roster[$i]['l5_eligible'] = $l5Eligible;
        $roster[$i]['l5_grade']    = $grade;
        $roster[$i]['l5_summary']  = ($l5Eligible && $grade) ? bulig_level5_summary($pdo, $p['id'], $grade) : null;
    }
} catch (Throwable $e) {
    $dbError = true;
}

$flashError = [
    'badscore' => 'Please fill in the word count and reading time.',
    'notyours' => "You can only score pupils you've added.",
    'notready' => 'This pupil needs to finish Level 3 before a fluency score can be recorded.',
    'server'   => 'Something went wrong on our end. Please try again.',
];
$scoreErrorMsg = isset($_GET['error']) ? ($flashError[$_GET['error']] ?? 'Something went wrong.') : null;
$scoreSuccess  = isset($_GET['scored']);

$statusChip = [
    'not_started' => ['chip-neutral',  'Not started'],
    'in_progress' => ['chip-progress', 'In progress'],
    'completed'   => ['chip-done',     'Completed'],
    'champion'    => ['chip-champion', '🏆 Champion'],
];

// Pulled straight from js/level1.js's PRETEST/POSTTEST so the questions
// shown here match exactly what the pupil answered.
$PRETEST = [
    1 => "Tell me your name and age, and one thing you like to do for fun.",
    2 => "Try this: 'Turn around and clap your hands once.' Describe what you did.",
    3 => "If you want your mom to give you a snack, what will you say?",
    4 => "Look at a red apple. What color is it? What shape is it?",
    5 => "What is your favorite toy? Tell one thing you like about it.",
    6 => "Describe a picture of a house so someone else could draw it.",
    7 => "Tell a short story about something you did yesterday, starting with 'I'.",
    8 => "Point to one thing you can see right now and name it. What color is it?",
    9 => "Do you like poems that rhyme? Why or why not?",
    10 => "Describe what the people or animals around you are doing right now.",
    11 => "I say 'sun' — what's one word that goes with 'sun'?",
    12 => "Try to say slowly: 'Red lorry, yellow lorry.'",
];
$POSTTEST = [
    1 => "Introduce yourself: name, age, one thing that makes you happy. Then describe your family.",
    2 => "Follow this: 'Close the door gently, and walk back to your seat.' Describe what you did, in order.",
    3 => "Ask your brother to turn off the TV and come to dinner — say it clearly with two steps.",
    4 => "Describe a toy near you — tell its color, size, and how it feels.",
    5 => "Tell me about your favorite game — how to play it and why you like it.",
    6 => "Describe a picture of a rainbow over a lake — the colors, and where things are.",
    7 => "Retell a partner's story, using 'he/she/they' as if you heard it from a friend.",
    8 => "Tell a short story (2-3 sentences) about children playing at a park.",
    9 => "Recite your favorite poem's first two lines from memory.",
    10 => "Add one sentence to a group story that starts: 'Once, a dog met a cat...'",
    11 => "I say 'food' — tell me two words related to 'food' and explain why.",
    12 => "Say 'How much wood would a woodchuck chuck?' once, at a steady pace.",
];

function bulig_render_answers(array $questions, object $answers): string
{
    $out = '';
    foreach ($questions as $lessonId => $q) {
        $a = $answers->{$lessonId} ?? '';
        $a = trim((string) $a);
        $out .= '<div class="ans-item"><div class="ans-q">Lesson ' . $lessonId . ' · ' . htmlspecialchars($q, ENT_QUOTES) . '</div>';
        $out .= $a === ''
            ? '<div class="ans-empty">(no answer yet)</div>'
            : '<div class="ans-a">' . nl2br(htmlspecialchars($a, ENT_QUOTES)) . '</div>';
        $out .= '</div>';
    }
    return $out;
}
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
            <h2 class="section-title">📊 Class Progress — Level 1 Oral Language</h2>

            <?php if ($dbError): ?>
                <div class="coming-soon">
                    <span class="cs-icon">⚠️</span>
                    <h1>Couldn't load progress</h1>
                    <p>There was a problem connecting to the database. Please check the connection settings and try again.</p>
                </div>
            <?php elseif (empty($roster)): ?>
                <div class="coming-soon">
                    <span class="cs-icon">🧒</span>
                    <h1>No pupils yet</h1>
                    <p>Add a pupil from the "My Pupils" page to start seeing progress here.</p>
                </div>
            <?php else: ?>

                <div class="data-card">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Pupil</th>
                                <th>Level</th>
                                <th>XP</th>
                                <th>Quests</th>
                                <th>Status</th>
                                <th>Pre-Test</th>
                                <th>Post-Test</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($roster as $p): [$chipClass, $chipLabel] = $statusChip[$p['status']];
                            $pct = (int) round((count($p['completed']) / $p['lessonsTotal']) * 100);
                        ?>
                            <tr>
                                <td style="font-weight:800;color:var(--ink);"><?= htmlspecialchars($p['name'], ENT_QUOTES) ?><br><span style="font-weight:600;color:var(--ink-soft);font-size:11.5px;">ID <?= htmlspecialchars($p['student_id'], ENT_QUOTES) ?></span></td>
                                <td><span class="chip chip-level"><?= bulig_level_label($p['current_level']) ?></span></td>
                                <td><?= $p['xp'] ?> XP</td>
                                <td>
                                    <?= count($p['completed']) ?>/<?= $p['lessonsTotal'] ?>
                                    <div class="mini-bar-track"><div class="mini-bar-fill" style="width:<?= $pct ?>%"></div></div>
                                </td>
                                <td><span class="chip <?= $chipClass ?>"><?= $chipLabel ?></span></td>
                                <td><?= $p['preDone']  ? '<span class="chip chip-done">Submitted</span>' : '<span class="chip chip-neutral">Not yet</span>' ?></td>
                                <td><?= $p['postDone'] ? '<span class="chip chip-done">Submitted</span>' : '<span class="chip chip-neutral">Not yet</span>' ?></td>
                            </tr>
                            <?php if ($p['preDone'] || $p['postDone']): ?>
                            <tr class="details-row">
                                <td colspan="7">
                                    <details>
                                        <summary>📋 View <?= htmlspecialchars($p['name'], ENT_QUOTES) ?>'s assessment answers</summary>
                                        <?php if ($p['preDone']): ?>
                                            <p style="font-weight:800;color:var(--bulig-green-dark);margin:12px 0 4px;">📝 Pre-Assessment</p>
                                            <div class="ans-panel"><?= bulig_render_answers($PRETEST, $p['preAnswers']) ?></div>
                                        <?php endif; ?>
                                        <?php if ($p['postDone']): ?>
                                            <p style="font-weight:800;color:var(--bulig-green-dark);margin:12px 0 4px;">🏅 Post-Assessment</p>
                                            <div class="ans-panel"><?= bulig_render_answers($POSTTEST, $p['postAnswers']) ?></div>
                                        <?php endif; ?>
                                    </details>
                                </td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p style="color:var(--ink-soft); font-weight:700; font-size:13px; margin-top:14px;">
                    Answers are pupil self-typed and not auto-graded — score them yourself using the Level 1 toolkit's rubric (Excellent / Good / Satisfactory / Needs Improvement).
                </p>

            <?php endif; ?>

            <h2 class="section-title" style="margin-top:34px;">🎙️ Level 4 Fluency Scoring</h2>
            <?php if ($scoreSuccess): ?><div class="form-success">✅ Fluency score saved.</div><?php endif; ?>
            <?php if ($scoreErrorMsg): ?><p class="form-alert" role="alert"><?= htmlspecialchars($scoreErrorMsg, ENT_QUOTES) ?></p><?php endif; ?>

            <?php
            $miscueFields = [
                'mispronunciation' => 'Mispronunciation',
                'omission'         => 'Omission',
                'substitution'     => 'Substitution',
                'insertion'        => 'Insertion',
                'repetition'       => 'Repetition',
                'transposition'    => 'Transposition',
                'reversal'         => 'Reversal',
            ];
            function bulig_render_l4_score_form(int $pupilId, string $slot, array $passage, array $miscueFields): string
            {
                $out  = '<form action="save_fluency_score.php" method="post" class="l4-score-form">';
                $out .= '<input type="hidden" name="pupil_id" value="' . $pupilId . '">';
                $out .= '<input type="hidden" name="slot" value="' . htmlspecialchars($slot, ENT_QUOTES) . '">';
                $out .= '<input type="hidden" name="word_count" value="' . (int) $passage['word_count'] . '">';
                $out .= '<p style="font-weight:700;font-size:12.5px;color:var(--ink-soft);">Passage: "' . htmlspecialchars($passage['title'], ENT_QUOTES) . '" (' . (int) $passage['word_count'] . ' words). Tally each miscue type while the pupil reads aloud, then enter their total reading time.</p>';
                $out .= '<div class="form-grid" style="margin-top:8px;">';
                foreach ($miscueFields as $key => $label) {
                    $out .= '<label class="field"><span class="field-label">' . $label . '</span><span class="field-control"><input type="number" min="0" name="miscue_' . $key . '" value="0" style="border:none;background:transparent;width:100%;padding:10px 12px;font-weight:700;"></span></label>';
                }
                $out .= '<label class="field"><span class="field-label">Reading Time (seconds)</span><span class="field-control"><input type="number" min="1" name="time_seconds" required style="border:none;background:transparent;width:100%;padding:10px 12px;font-weight:700;"></span></label>';
                $out .= '</div><div class="form-actions"><button type="submit" class="btn-mini" style="padding:10px 20px;">💾 Save Score</button></div></form>';
                return $out;
            }
            ?>

            <?php if ($dbError): ?>
                <div class="coming-soon">
                    <span class="cs-icon">⚠️</span>
                    <h1>Couldn't load fluency data</h1>
                    <p>There was a problem connecting to the database. Please check the connection settings and try again.</p>
                </div>
            <?php else:
                $l4Pupils = array_filter($roster, fn($p) => $p['l4_eligible'] && $p['l4_grade']);
            ?>
                <?php if (empty($l4Pupils)): ?>
                <div class="coming-soon">
                    <span class="cs-icon">🎙️</span>
                    <h1>No pupils ready for Level 4 yet</h1>
                    <p>Fluency scoring appears here once a pupil has finished Level 3 <em>and</em> has a Grade set on the My Pupils page.</p>
                </div>
                <?php else: ?>
                <div class="data-card">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Pupil</th>
                                <th>Grade</th>
                                <th>Passages Practiced</th>
                                <th>Pre-Test Score</th>
                                <th>Post-Test Score</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($l4Pupils as $p):
                            $s4 = $p['l4_summary'];
                            $c4 = $p['l4_content'];
                            $allPracticed = count($s4['completed']) === $s4['lessonsTotal'] && $s4['lessonsTotal'] > 0;
                        ?>
                            <tr id="l4-<?= $p['id'] ?>">
                                <td style="font-weight:800;color:var(--ink);"><?= htmlspecialchars($p['name'], ENT_QUOTES) ?><br><span style="font-weight:600;color:var(--ink-soft);font-size:11.5px;">ID <?= htmlspecialchars($p['student_id'], ENT_QUOTES) ?></span></td>
                                <td><span class="chip chip-level">Grade <?= $p['l4_grade'] ?></span></td>
                                <td><?= count($s4['completed']) ?>/<?= $s4['lessonsTotal'] ?></td>
                                <td><?= $s4['preScore'] ? htmlspecialchars((string) $s4['preScore']['oralReadingScore'], ENT_QUOTES) . '% (' . htmlspecialchars((string) $s4['preScore']['readingLevel'], ENT_QUOTES) . ')' : '<span class="chip chip-neutral">Not yet</span>' ?></td>
                                <td><?= $s4['postScore'] ? htmlspecialchars((string) $s4['postScore']['oralReadingScore'], ENT_QUOTES) . '% (' . htmlspecialchars((string) $s4['postScore']['readingLevel'], ENT_QUOTES) . ')' : '<span class="chip chip-neutral">Not yet</span>' ?></td>
                            </tr>
                            <tr class="details-row">
                                <td colspan="5">
                                    <details>
                                        <summary>🎙️ Record/update <?= htmlspecialchars($p['name'], ENT_QUOTES) ?>'s fluency score</summary>
                                        <p style="font-weight:800;color:var(--bulig-green-dark);margin:12px 0 4px;">📝 Pre-Test</p>
                                        <?= bulig_render_l4_score_form($p['id'], 'pre', $c4['pretest'], $miscueFields) ?>
                                        <p style="font-weight:800;color:var(--bulig-green-dark);margin:16px 0 4px;">🏁 Post-Test</p>
                                        <?php if ($allPracticed): ?>
                                            <?= bulig_render_l4_score_form($p['id'], 'post', $c4['posttest'], $miscueFields) ?>
                                        <?php else: ?>
                                            <p style="color:var(--ink-soft);font-weight:700;">🔒 Available once all <?= $s4['lessonsTotal'] ?> Grade <?= $p['l4_grade'] ?> passages are practiced (<?= count($s4['completed']) ?>/<?= $s4['lessonsTotal'] ?> so far).</p>
                                        <?php endif; ?>
                                    </details>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p style="color:var(--ink-soft); font-weight:700; font-size:13px; margin-top:14px;">
                    Oral Reading Score = (words read correctly ÷ total words) × 100. 98-100% = Independent, 90-97% = Instructional, 89% and below = Frustration — same thresholds as the module's own Marking and Scoring Guide.
                </p>
                <?php endif; ?>
            <?php endif; ?>

            <h2 class="section-title" style="margin-top:34px;">👂 Level 5 Listening &amp; Vocabulary</h2>
            <?php if ($dbError): ?>
                <div class="coming-soon">
                    <span class="cs-icon">⚠️</span>
                    <h1>Couldn't load Level 5 data</h1>
                    <p>There was a problem connecting to the database. Please check the connection settings and try again.</p>
                </div>
            <?php else:
                $l5Pupils = array_filter($roster, fn($p) => $p['l5_eligible'] && $p['l5_grade']);
            ?>
                <?php if (empty($l5Pupils)): ?>
                <div class="coming-soon">
                    <span class="cs-icon">👂</span>
                    <h1>No pupils ready for Level 5 yet</h1>
                    <p>This appears once a pupil has finished Level 4 <em>and</em> has a Grade set on the My Pupils page. Level 5 is self-scored by the pupil, so there's nothing for you to record here — just track their progress.</p>
                </div>
                <?php else: ?>
                <div class="data-card">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Pupil</th>
                                <th>Grade</th>
                                <th>Activities Completed</th>
                                <th>XP</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($l5Pupils as $p): $s5 = $p['l5_summary']; $total5 = max(1, $s5['lessonsTotal']); $pct5 = (int) round(count($s5['completed']) / $total5 * 100); ?>
                            <tr>
                                <td style="font-weight:800;color:var(--ink);"><?= htmlspecialchars($p['name'], ENT_QUOTES) ?><br><span style="font-weight:600;color:var(--ink-soft);font-size:11.5px;">ID <?= htmlspecialchars($p['student_id'], ENT_QUOTES) ?></span></td>
                                <td><span class="chip chip-level">Grade <?= $p['l5_grade'] ?></span></td>
                                <td>
                                    <?= count($s5['completed']) ?>/<?= $s5['lessonsTotal'] ?>
                                    <div class="mini-bar-track"><div class="mini-bar-fill" style="width:<?= $pct5 ?>%"></div></div>
                                </td>
                                <td><?= $s5['xp'] ?> XP</td>
                                <td><?= count($s5['completed']) >= $s5['lessonsTotal'] && $s5['lessonsTotal'] > 0 ? '<span class="chip chip-champion">🏆 Champion</span>' : (count($s5['completed']) > 0 ? '<span class="chip chip-progress">In progress</span>' : '<span class="chip chip-neutral">Not started</span>') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p style="color:var(--ink-soft); font-weight:700; font-size:13px; margin-top:14px;">
                    Level 5 content is still being added grade-by-grade — some pupils may see fewer than 20 activities marked "Content Coming Soon" until the rest is transcribed.
                </p>
                <?php endif; ?>
            <?php endif; ?>
        </main>
    </div>
</div>
<script src="../js/sidebar.js"></script>
</body>
</html>
