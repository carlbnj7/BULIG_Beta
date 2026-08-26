<?php
/**
 * BULIG — Level 1 progress helpers.
 * One shared query used by dashboard.php, progress.php, achievements.php,
 * and level1.php so the XP/lesson/badge numbers can never drift apart.
 */
declare(strict_types=1);

const BULIG_L1_LESSON_COUNT      = 12;
const BULIG_L1_XP_PER_LESSON     = 50;
const BULIG_L1_XP_PER_ASSESSMENT = 40;

/**
 * @return array{xp:int, completed:int[], lessonsTotal:int, preDone:bool,
 *               postDone:bool, preAnswers:object, postAnswers:object,
 *               streakDays:int}
 */
function bulig_level1_summary(PDO $pdo, int $pupilId): array
{
    $stmt = $pdo->prepare(
        'SELECT lesson_id, xp_earned, answer_text, completed_at
         FROM pupil_progress
         WHERE pupil_id = :pid AND level_id = 1'
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

        if ($lessonId >= 1 && $lessonId <= BULIG_L1_LESSON_COUNT) {
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
        'lessonsTotal' => BULIG_L1_LESSON_COUNT,
        'preDone'      => $preDone,
        'postDone'     => $postDone,
        'preAnswers'   => $preAnswers,
        'postAnswers'  => $postAnswers,
        'streakDays'   => bulig_calc_streak($dates),
    ];
}

/** Consecutive calendar days (ending today or yesterday) with a completion. */
function bulig_calc_streak(array $dates): int
{
    if (empty($dates)) {
        return 0;
    }
    $unique = array_unique($dates);
    rsort($unique);

    $streak = 0;
    $cursor = new DateTimeImmutable('today');

    foreach ($unique as $dateStr) {
        $day = new DateTimeImmutable($dateStr);
        if ($day->format('Y-m-d') === $cursor->format('Y-m-d')) {
            $streak++;
            $cursor = $cursor->modify('-1 day');
        } elseif ($day < $cursor) {
            break;
        }
    }
    return $streak;
}
