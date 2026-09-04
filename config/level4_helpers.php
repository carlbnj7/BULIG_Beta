<?php
/**
 * BULIG — Level 4 (Reading Fluency, Grades 1-6) progress helpers.
 * Digitizes the module "LEVEL 4 FLUENCY", which ships as six separate
 * grade-specific booklets (LEVEL-4-FLUENCY-GRADE-1.pdf ... GRADE-6.pdf).
 * Each grade booklet has: one Pre-test passage, a set of intervention
 * (practice) passages, and a Post-test passage that rereads the exact
 * same text as the pretest — a standard oral-reading-fluency (Phil-IRI
 * style) design used to measure improvement.
 *
 * Level 4 is GRADE-GATED: a pupil only ever sees the passages for their
 * own `pupils.grade_level` (managed by the teacher on teacher/pupils.php
 * — see teacher/assign_grade.php). This is different from Levels 1-2B/3,
 * which are the same for everyone.
 *
 * It's also SCORED DIFFERENTLY: fluency is measured by a teacher
 * listening to the pupil read aloud and marking miscues (mispronunciation,
 * omission, substitution, insertion, repetition, transposition, reversal)
 * — exactly like the module's own "Marking and Scoring Guide" — so the
 * Pre-test/Post-test scores are entered by the TEACHER (on
 * teacher/progress.php, via teacher/save_fluency_score.php), not typed by
 * the pupil. The intervention passages are pupil-paced reading practice:
 * the pupil listens (Read Aloud/TTS) and marks each one practiced to earn
 * XP, same gamification pattern as every other level.
 *
 * Level 4 reuses the same level-agnostic `pupil_progress` table as every
 * other level (level_id = 5, see sql/level4_progress.sql) — no schema
 * change was needed.
 */
declare(strict_types=1);

const BULIG_L4_LEVEL_ID          = 5;
const BULIG_L4_XP_PER_PASSAGE    = 20;  // pupil marks an intervention passage as practiced
const BULIG_L4_XP_PER_ASSESSMENT = 60;  // teacher records a pre-/post-test fluency score

/**
 * How many intervention (practice) passages each grade's real module
 * actually contains — matches js/level4.js's GRADE_CONTENT exactly.
 * Grades 4 and 5/6 aren't all "10" because two of the source PDFs have a
 * documented content gap (Grade 4's own Table of Contents lists a "Songs
 * of the Witches II" that isn't actually a distinct passage in the book —
 * the page that should hold it is a misprint duplicate of another
 * passage's page) — see the "KNOWN SOURCE-DOCUMENT DISCREPANCIES" comment
 * in js/level4.js for details. Nothing was skipped on our end; this is
 * what's actually printed in each grade's booklet.
 */
const BULIG_L4_INTERVENTION_COUNTS = [
    1 => 10,
    2 => 10,
    3 => 10,
    4 => 9,
    5 => 8,
    6 => 9,
];

/** A pupil's real, current grade — always read fresh from the database
 *  (never trusted from session), since a teacher can change it at any
 *  time on teacher/pupils.php and that must take effect immediately. */
function bulig_pupil_grade(PDO $pdo, int $pupilId): ?int
{
    $stmt = $pdo->prepare('SELECT grade_level FROM pupils WHERE id = :pid');
    $stmt->execute(['pid' => $pupilId]);
    $row = $stmt->fetch();
    if (!$row || $row['grade_level'] === null) {
        return null;
    }
    $g = (int) $row['grade_level'];
    return ($g >= 1 && $g <= 6) ? $g : null;
}

/**
 * @return array{xp:int, completed:int[], lessonsTotal:int, preDone:bool,
 *               postDone:bool, preScore:?array, postScore:?array,
 *               streakDays:int}
 */
function bulig_level4_summary(PDO $pdo, int $pupilId, int $grade): array
{
    $lessonsTotal = BULIG_L4_INTERVENTION_COUNTS[$grade] ?? 0;

    $stmt = $pdo->prepare(
        'SELECT lesson_id, xp_earned, answer_text, completed_at
         FROM pupil_progress
         WHERE pupil_id = :pid AND level_id = ' . BULIG_L4_LEVEL_ID
    );
    $stmt->execute(['pid' => $pupilId]);
    $rows = $stmt->fetchAll();

    $completed = [];
    $xp        = 0;
    $preDone   = false;
    $postDone  = false;
    $preScore  = null;
    $postScore = null;
    $dates     = [];

    foreach ($rows as $r) {
        $lessonId = (int) $r['lesson_id'];
        $xp += (int) $r['xp_earned'];

        if ($lessonId >= 1 && $lessonId <= $lessonsTotal) {
            $completed[] = $lessonId;
            if (!empty($r['completed_at'])) {
                $dates[] = substr((string) $r['completed_at'], 0, 10);
            }
        } elseif ($lessonId === 0) {
            $preDone  = true;
            $preScore = json_decode($r['answer_text'] ?: '{}', true);
        } elseif ($lessonId === 100) {
            $postDone  = true;
            $postScore = json_decode($r['answer_text'] ?: '{}', true);
        }
    }
    sort($completed);

    return [
        'xp'           => $xp,
        'completed'    => $completed,
        'lessonsTotal' => $lessonsTotal,
        'preDone'      => $preDone,
        'postDone'     => $postDone,
        'preScore'     => $preScore,
        'postScore'    => $postScore,
        'streakDays'   => bulig_calc_streak($dates), // lives in level1_helpers.php
    ];
}

/**
 * Turns a teacher's raw miscue tally into the same numbers the module's
 * own "Marking and Scoring Guide" produces by hand:
 *   Oral Reading Score = (words read correctly / total words) x 100
 *   Reading Level       = Independent 98-100, Instructional 90-97,
 *                          Frustration 89 and below
 *   Reading Speed (WPM) = words in passage / (seconds / 60)
 *
 * @param array<string,int> $miscues keys: mispronunciation, omission,
 *   substitution, insertion, repetition, transposition, reversal
 */
function bulig_l4_compute_score(int $wordCount, array $miscues, int $timeSeconds): array
{
    $totalMiscues = 0;
    foreach (['mispronunciation', 'omission', 'substitution', 'insertion', 'repetition', 'transposition', 'reversal'] as $k) {
        $totalMiscues += max(0, (int) ($miscues[$k] ?? 0));
    }
    $totalMiscues = min($totalMiscues, $wordCount);

    $scorePct = $wordCount > 0 ? round((($wordCount - $totalMiscues) / $wordCount) * 100, 1) : 0.0;

    if ($scorePct >= 98) {
        $readingLevel = 'Independent';
    } elseif ($scorePct >= 90) {
        $readingLevel = 'Instructional';
    } else {
        $readingLevel = 'Frustration';
    }

    $wpm = $timeSeconds > 0 ? (int) round($wordCount / $timeSeconds * 60) : 0;

    return [
        'miscues'          => $miscues,
        'totalMiscues'     => $totalMiscues,
        'wordCount'        => $wordCount,
        'timeSeconds'      => $timeSeconds,
        'oralReadingScore' => $scorePct,
        'readingLevel'     => $readingLevel,
        'wpm'              => $wpm,
    ];
}

/**
 * Level 5 is locked until every one of the pupil's grade's Level 4
 * intervention passages is practiced. Grade-gated the same way Level 4
 * itself is gated behind Level 3 — added here (not level5_helpers.php)
 * to match how level3_helpers.php holds its own bulig_level3_is_complete.
 * A pupil with no grade assigned yet can't be "complete" (nothing to
 * measure against), so this returns false rather than erroring.
 */
function bulig_level4_is_complete(PDO $pdo, int $pupilId, ?int $grade): bool
{
    if ($grade === null) {
        return false;
    }
    $l4 = bulig_level4_summary($pdo, $pupilId, $grade);
    return count($l4['completed']) >= $l4['lessonsTotal'] && $l4['lessonsTotal'] > 0;
}
