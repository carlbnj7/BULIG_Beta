<?php
/**
 * BULIG — Level 5 (Listening Comprehension & Vocabulary Development)
 * progress helpers, Grades 1-6.
 *
 * GRADE-GATED exactly like Level 4: a pupil only ever sees their own
 * `pupils.grade_level`'s 20 activities (see teacher/assign_grade.php).
 * Unlike Level 4, Level 5 is fully PUPIL-SCORED (self-answered quizzes),
 * the same gamification pattern as Levels 1-3 — no teacher scoring form
 * is needed here.
 *
 * Reuses the level-agnostic `pupil_progress` table at level_id = 6 (see
 * sql/level5_progress.sql) — no schema change needed.
 */
declare(strict_types=1);

const BULIG_L5_LEVEL_ID          = 6;
const BULIG_L5_XP_PER_ACTIVITY   = 30;

/** Real per-grade activity count — currently 20 for every grade (see config/level5_content.php). */
function bulig_level5_lessons_total(int $grade): int
{
    require_once __DIR__ . '/level5_content.php';
    return bulig_level5_activity_count($grade);
}

/**
 * @return array{xp:int, completed:int[], lessonsTotal:int, streakDays:int}
 */
function bulig_level5_summary(PDO $pdo, int $pupilId, int $grade): array
{
    $lessonsTotal = bulig_level5_lessons_total($grade);

    $stmt = $pdo->prepare(
        'SELECT lesson_id, xp_earned, completed_at
         FROM pupil_progress
         WHERE pupil_id = :pid AND level_id = ' . BULIG_L5_LEVEL_ID
    );
    $stmt->execute(['pid' => $pupilId]);
    $rows = $stmt->fetchAll();

    $completed = [];
    $xp        = 0;
    $dates     = [];

    foreach ($rows as $r) {
        $lessonId = (int) $r['lesson_id'];
        $xp += (int) $r['xp_earned'];
        if ($lessonId >= 1 && $lessonId <= $lessonsTotal) {
            $completed[] = $lessonId;
            if (!empty($r['completed_at'])) {
                $dates[] = substr((string) $r['completed_at'], 0, 10);
            }
        }
    }
    sort($completed);

    return [
        'xp'           => $xp,
        'completed'    => $completed,
        'lessonsTotal' => $lessonsTotal,
        'streakDays'   => bulig_calc_streak($dates), // lives in level1_helpers.php
    ];
}

/** Level 6 doesn't exist yet, but kept for pattern consistency / future use. */
function bulig_level5_is_complete(PDO $pdo, int $pupilId, ?int $grade): bool
{
    if ($grade === null) {
        return false;
    }
    $l5 = bulig_level5_summary($pdo, $pupilId, $grade);
    return count($l5['completed']) >= $l5['lessonsTotal'] && $l5['lessonsTotal'] > 0;
}
