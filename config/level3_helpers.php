<?php
/**
 * BULIG — Level 3 (Word Recognition) progress helpers.
 * Mirrors config/level1_helpers.php and config/level2_helpers.php so all
 * three levels stay consistent. Level 3 shares the same `pupil_progress`
 * table too — it just uses level_id = 4, so no schema change was needed
 * (see sql/level3_progress.sql for the doc-only note, same pattern as
 * sql/level2_progress.sql).
 *
 * Level 3 covers the FULL 144-page "Level 3 – Word Recognition" module:
 * 25 Lessons (CVC short vowels, long-vowel word families, consonant
 * blends, and Fry's sight words), each containing every one of its real
 * numbered Activities as sequential stages — same pattern Level 2A used
 * for its 8 units. The module itself is split into two booklets, Level
 * 3A (Lessons 1-10) and Level 3B (Lessons 11-25), each with its own
 * Pre-/Post-Assessment Toolkit — those four toolkits are combined into
 * one Pre-Assessment (unlocked at the start) and one Post-Assessment
 * (unlocked once all 25 lessons are done), matching how Level 1 and
 * Level 2A each run a single pre/post pair.
 *
 * BUGFIX: this used to be level_id = 3, which silently collided with
 * Level 2B (config/level2b_helpers.php also uses level_id = 3) — the two
 * levels' rows shared the same (pupil_id, level_id, lesson_id) unique key,
 * corrupting both levels' XP/completion for any pupil who did both. Moved
 * to level_id = 4 so every level now has its own slot:
 *   1 = Level 1, 2 = Level 2A, 3 = Level 2B, 4 = Level 3, 5 = Level 4.
 * See BULIG_FIXES.md for the migration note on any existing level_id = 3
 * data from before this fix.
 */
declare(strict_types=1);

const BULIG_L3_LEVEL_ID          = 4;
const BULIG_L3_LESSON_COUNT      = 25;
const BULIG_L3_XP_PER_LESSON     = 70;   // 25 lessons, each 2-5 real activities
const BULIG_L3_XP_PER_ASSESSMENT = 50;

/**
 * @return array{xp:int, completed:int[], lessonsTotal:int, preDone:bool,
 *               postDone:bool, preAnswers:object, postAnswers:object,
 *               streakDays:int}
 */
function bulig_level3_summary(PDO $pdo, int $pupilId): array
{
    $stmt = $pdo->prepare(
        'SELECT lesson_id, xp_earned, answer_text, completed_at
         FROM pupil_progress
         WHERE pupil_id = :pid AND level_id = ' . BULIG_L3_LEVEL_ID
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

        if ($lessonId >= 1 && $lessonId <= BULIG_L3_LESSON_COUNT) {
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
        'lessonsTotal' => BULIG_L3_LESSON_COUNT,
        'preDone'      => $preDone,
        'postDone'     => $postDone,
        'preAnswers'   => $preAnswers,
        'postAnswers'  => $postAnswers,
        'streakDays'   => bulig_calc_streak($dates), // lives in level1_helpers.php
    ];
}

/**
 * Level 3 is locked until every Level 2A quest is complete.
 * Reuses bulig_level2_summary() — level1/level2/level3 helper files are
 * always loaded together on pages that need this check.
 */
function bulig_level2b_is_complete(PDO $pdo, int $pupilId): bool
{
    $l2b = bulig_level2b_summary($pdo, $pupilId);
    return count($l2b['completed']) >= BULIG_L2B_LESSON_COUNT;
}

/**
 * Level 4 is locked until every Level 3 quest is complete.
 * Previously called from pupil/progress.php but never defined — that page
 * fatally errored on load. See BULIG_FIXES.md.
 */
function bulig_level3_is_complete(PDO $pdo, int $pupilId): bool
{
    $l3 = bulig_level3_summary($pdo, $pupilId);
    return count($l3['completed']) >= BULIG_L3_LESSON_COUNT;
}
