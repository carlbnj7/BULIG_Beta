<?php
/**
 * BULIG — Level 2B (Phonological Awareness, Book 2) progress helpers.
 * Mirrors config/level2_helpers.php exactly, one level down: Level 2B just
 * uses level_id = 3 instead of level_id = 2, so — same as Level 2A before
 * it — no schema change was needed. See sql/level2b_progress.sql for the
 * note on this.
 *
 * Level 2B digitizes the module "BULIG Level 2 – Phonological Awareness"
 * (Book 2 / continuation booklet): 6 skill categories covering all 22 of
 * the module's real numbered Activities (1-22), grouped exactly the way
 * the module's own Table of Contents groups them:
 *
 *   Unit 1 - Blending Onsets and Rimes into Words   (Activities 1-4)
 *   Unit 2 - Segmenting Words into Onsets and Rimes  (Activities 5-7)
 *   Unit 3 - Segmenting Words into Syllables         (Activities 8-10)
 *   Unit 4 - Blending Syllables into Words           (Activities 11-15)
 *   Unit 5 - Sentence Segmentation                   (Activities 16-18)
 *   Unit 6 - Rhymes and Rhyming Songs                (Activities 19-22)
 *
 * Note on activity count: the module's Table of Contents lists an
 * "Activity 23" under Rhymes and Rhyming Songs (p.38), but the module
 * itself only contains worksheets for Activities 19-22 there before the
 * Post-Assessment divider page — Activity 23 does not physically exist
 * in the source document. Level 2B therefore ships the 22 activities
 * that actually exist, rather than inventing content for a 23rd. Nothing
 * from the module was skipped or combined to reach this number.
 */
declare(strict_types=1);

const BULIG_L2B_LEVEL_ID          = 3;
const BULIG_L2B_LESSON_COUNT      = 6;
const BULIG_L2B_XP_PER_LESSON     = 100;  // each unit covers 3-5 real activities as sequential stages
const BULIG_L2B_XP_PER_ASSESSMENT = 50;

/**
 * @return array{xp:int, completed:int[], lessonsTotal:int, preDone:bool,
 *               postDone:bool, preAnswers:object, postAnswers:object,
 *               streakDays:int}
 */
function bulig_level2b_summary(PDO $pdo, int $pupilId): array
{
    $stmt = $pdo->prepare(
        'SELECT lesson_id, xp_earned, answer_text, completed_at
         FROM pupil_progress
         WHERE pupil_id = :pid AND level_id = ' . BULIG_L2B_LEVEL_ID
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

        if ($lessonId >= 1 && $lessonId <= BULIG_L2B_LESSON_COUNT) {
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
        'lessonsTotal' => BULIG_L2B_LESSON_COUNT,
        'preDone'      => $preDone,
        'postDone'     => $postDone,
        'preAnswers'   => $preAnswers,
        'postAnswers'  => $postAnswers,
        'streakDays'   => bulig_calc_streak($dates), // bulig_calc_streak() lives in level1_helpers.php
    ];
}

/**
 * Level 2B is locked until every Level 2A quest is complete — it's the
 * continuation booklet, so it follows Level 2A the same way Level 2A
 * follows Level 1.
 */
function bulig_level2_is_complete(PDO $pdo, int $pupilId): bool
{
    $l2 = bulig_level2_summary($pdo, $pupilId);
    return count($l2['completed']) >= BULIG_L2_LESSON_COUNT;
}
