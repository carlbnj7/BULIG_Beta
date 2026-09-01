<?php
/**
 * BULIG — Teacher: record a pupil's Level 4 (Fluency) pre-/post-test
 * score. This is the "Progress/Score → Teacher Monitoring" step of the
 * Level 4 pipeline: fluency is measured by a teacher listening to a
 * pupil read a passage aloud and tallying miscues (mispronunciation,
 * omission, substitution, insertion, repetition, transposition,
 * reversal) plus the reading time — exactly like the module's own
 * "Marking and Scoring Guide" — so this is entered here, not typed by
 * the pupil. bulig_l4_compute_score() turns that tally into the Oral
 * Reading Score %, Reading Level, and words-per-minute automatically.
 */
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/level1_helpers.php';
require_once __DIR__ . '/../config/level3_helpers.php';
require_once __DIR__ . '/../config/level4_helpers.php';
require_teacher_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: progress.php');
    exit;
}

$teacherPk   = (int) $_SESSION['teacher_pk'];
$pupilId     = (int) ($_POST['pupil_id'] ?? 0);
$slotName    = (string) ($_POST['slot'] ?? '');
$wordCount   = (int) ($_POST['word_count'] ?? 0);
$timeSeconds = (int) ($_POST['time_seconds'] ?? 0);

$slot = $slotName === 'pre' ? 0 : ($slotName === 'post' ? 100 : null);

if ($pupilId <= 0 || $slot === null || $wordCount <= 0 || $timeSeconds <= 0) {
    header('Location: progress.php?error=badscore#l4-' . $pupilId);
    exit;
}

$miscues = [];
foreach (['mispronunciation', 'omission', 'substitution', 'insertion', 'repetition', 'transposition', 'reversal'] as $k) {
    $miscues[$k] = max(0, (int) ($_POST['miscue_' . $k] ?? 0));
}

try {
    $pdo = get_db_connection();

    // Ownership check: this pupil must belong to the teacher, or be a
    // legacy/unassigned pupil, exactly like assign_level.php / assign_grade.php.
    $own = $pdo->prepare('SELECT id, current_level FROM pupils WHERE id = :pid AND (teacher_id = :tid OR teacher_id IS NULL)');
    $own->execute(['pid' => $pupilId, 'tid' => $teacherPk]);
    $pupilRow = $own->fetch();
    if (!$pupilRow) {
        header('Location: progress.php?error=notyours#l4-' . $pupilId);
        exit;
    }

    if (!bulig_level3_is_complete($pdo, $pupilId)) {
        header('Location: progress.php?error=notready#l4-' . $pupilId);
        exit;
    }

    $score = bulig_l4_compute_score($wordCount, $miscues, $timeSeconds);
    $answerJson = json_encode($score);

    $check = $pdo->prepare('SELECT id FROM pupil_progress WHERE pupil_id = :p AND level_id = ' . BULIG_L4_LEVEL_ID . ' AND lesson_id = :l');
    $check->execute(['p' => $pupilId, 'l' => $slot]);
    $existing = $check->fetch();

    if ($existing) {
        $upd = $pdo->prepare('UPDATE pupil_progress SET answer_text = :a WHERE id = :id');
        $upd->execute(['a' => $answerJson, 'id' => $existing['id']]);
    } else {
        $ins = $pdo->prepare(
            'INSERT INTO pupil_progress (pupil_id, level_id, lesson_id, xp_earned, answer_text) VALUES (:p, ' . BULIG_L4_LEVEL_ID . ', :l, :xp, :a)'
        );
        $ins->execute(['p' => $pupilId, 'l' => $slot, 'xp' => BULIG_L4_XP_PER_ASSESSMENT, 'a' => $answerJson]);
    }

    header('Location: progress.php?scored=1#l4-' . $pupilId);
    exit;
} catch (Throwable $e) {
    header('Location: progress.php?error=server#l4-' . $pupilId);
    exit;
}
