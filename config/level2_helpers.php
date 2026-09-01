<?php
/**
 * BULIG — Level 2A (Phonological Awareness) progress helpers.
 * Mirrors config/level1_helpers.php so the two levels stay consistent.
 * Both levels share the same `pupil_progress` table — Level 2A just uses
 * level_id = 2 instead of 1, so no schema change was needed (see
 * sql/level2_progress.sql for the note on this).
 */
declare(strict_types=1);

const BULIG_L2_LEVEL_ID          = 2;
const BULIG_L2_LESSON_COUNT      = 20;  // one node per real module Activity (1-20), matching the module's own numbering
const BULIG_L2_XP_PER_LESSON     = 50;  // same per-activity XP as Level 1, since granularity now matches (20 single activities, not multi-stage units)
const BULIG_L2_XP_PER_ASSESSMENT = 40;

/**
 * @return array{xp:int, completed:int[], lessonsTotal:int, preDone:bool,
 *               postDone:bool, preAnswers:object, postAnswers:object,
 *               streakDays:int}
 */
function bulig_level2_summary(PDO $pdo, int $pupilId): array
{
    $stmt = $pdo->prepare(
        'SELECT lesson_id, xp_earned, answer_text, completed_at
         FROM pupil_progress
         WHERE pupil_id = :pid AND level_id = ' . BULIG_L2_LEVEL_ID
    );
    $stmt->execute(['pid' => $pupilId]);
    $rows = $stmt->fetchAll();

    $completed   = [];
    $xp          = 0;
    $preDone     = false;
    $postDone    = false;
    $preAnswers  = new stdClass();
    $postAnswers = new stdClass();
    $dates       = [];

    foreach ($rows as $r) {
        $lessonId = (int) $r['lesson_id'];
        $xp += (int) $r['xp_earned'];

        if ($lessonId >= 1 && $lessonId <= BULIG_L2_LESSON_COUNT) {
            $completed[] = $lessonId;
            if (!empty($r['completed_at'])) {
                $dates[] = substr((string) $r['completed_at'], 0, 10);
            }
        } elseif ($lessonId === 0) {
            $preDone = true;
            $preAnswers = json_decode($r['answer_text'] ?: '{}');
        } elseif ($lessonId === 100) {
            $postDone = true;
            $postAnswers = json_decode($r['answer_text'] ?: '{}');
        }
    }
    sort($completed);

    return [
        'xp'           => $xp,
        'completed'    => $completed,
        'lessonsTotal' => BULIG_L2_LESSON_COUNT,
        'preDone'      => $preDone,
        'postDone'     => $postDone,
        'preAnswers'   => $preAnswers,
        'postAnswers'  => $postAnswers,
        'streakDays'   => bulig_calc_streak($dates), // bulig_calc_streak() lives in level1_helpers.php
    ];
}

/**
 * Level 2A is locked until every Level 1 quest is complete.
 * Reuses bulig_level1_summary() from level1_helpers.php — both helper
 * files are always loaded together on pages that need this check.
 */
function bulig_level1_is_complete(PDO $pdo, int $pupilId): bool
{
    $l1 = bulig_level1_summary($pdo, $pupilId);
    return count($l1['completed']) >= BULIG_L1_LESSON_COUNT;
}
