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
 * Highest level a teacher can assign a pupil to / that "Starting Level"
 * and "Level" dropdowns should offer.
 *   1 = Level 1 (Oral Language)              5 = Level 4 (Fluency, per-grade)
 *   2 = Level 2A (Phonological Awareness)
 *   3 = Level 2B (Phonological Awareness II)
 *   4 = Level 3 (Word Recognition)
 * Bumped from a previously-undefined constant that crashed every page
 * that referenced it (teacher/pupils.php, add_pupil.php, assign_level.php)
 * — see BULIG_FIXES.md for the full list of fixed bugs.
 */
const BULIG_MAX_LEVEL = 6;

/**
 * Display label for a `pupils.current_level` value — this is the number
 * that goes in the "Level" dropdown/column on teacher/pupils.php, so it
 * needs to say the pupil-facing name (Level 2A/2B, not just "Level 2"
 * and "Level 3"), or a teacher has no way to tell those apart.
 */
const BULIG_LEVEL_LABELS = [
    1 => 'Level 1',
    2 => 'Level 2A',
    3 => 'Level 2B',
    4 => 'Level 3',
    5 => 'Level 4',
    6 => 'Level 5',
];
function bulig_level_label(int $level): string
{
    return BULIG_LEVEL_LABELS[$level] ?? ('Level ' . $level);
}

/**
 * Reads a pupil's teacher-assigned `current_level` fresh from the
 * database (never trust a session value for this — a teacher can
 * reassign it at any time, and it must take effect immediately).
 * Falls back to 1 (Level 1) if the pupil can't be found for any reason,
 * which is the safest default (nothing gets force-unlocked).
 */
function bulig_pupil_current_level(PDO $pdo, int $pupilId): int
{
    $stmt = $pdo->prepare('SELECT current_level FROM pupils WHERE id = :id');
    $stmt->execute(['id' => $pupilId]);
    $level = (int) $stmt->fetchColumn();
    return ($level >= 1 && $level <= BULIG_MAX_LEVEL) ? $level : 1;
}

/**
 * Whether a level should be accessible to a pupil. True the normal way
 * (the level right before it is actually completed) OR because a
 * teacher directly assigned/advanced the pupil to this level or beyond
 * on teacher/pupils.php ("Starting Level" at signup, or the "Level"
 * dropdown afterwards) — e.g. a fluent pupil a teacher places straight
 * into Level 3 sees Level 3 (and everything before it) unlocked right
 * away, without having to grind through 1 → 2A → 2B first. Levels past
 * the assigned one still unlock the normal, earned way.
 */
function bulig_level_unlocked(int $levelId, bool $previousLevelComplete, int $assignedLevel): bool
{
    return $previousLevelComplete || $assignedLevel >= $levelId;
}

/**
 * A pupil's most recent completed activities across every level, newest
 * first — powers the "recent activity" list under their avatar on the
 * dashboard. Reads straight off `pupil_progress`, so it's always in
 * sync with real completions (nothing separate to keep updated).
 */
function bulig_pupil_recent_activity(PDO $pdo, int $pupilId, int $limit = 5): array
{
    $stmt = $pdo->prepare(
        'SELECT level_id, lesson_id, xp_earned, completed_at
         FROM pupil_progress
         WHERE pupil_id = :pid
         ORDER BY completed_at DESC
         LIMIT ' . max(1, $limit)
    );
    $stmt->execute(['pid' => $pupilId]);
    $rows = $stmt->fetchAll();

    $icons = [1 => '🚀', 2 => '🔤', 3 => '🔡', 4 => '📖', 5 => '🎙️', 6 => '👂'];
    $out = [];
    foreach ($rows as $r) {
        $levelId = (int) $r['level_id'];
        $lessonId = (int) $r['lesson_id'];
        $what = $lessonId === 0 ? 'Pre-Assessment' : ($lessonId === 100 ? 'Post-Assessment' : 'Lesson ' . $lessonId);
        $out[] = [
            'icon' => $icons[$levelId] ?? '⭐',
            'text' => bulig_level_label($levelId) . ' — ' . $what,
            'xp'   => (int) $r['xp_earned'],
            'when' => bulig_time_ago((string) $r['completed_at']),
        ];
    }
    return $out;
}

/** Tiny "3 days ago" / "just now" formatter, no external library needed. */
function bulig_time_ago(string $datetime): string
{
    $then = new DateTimeImmutable($datetime);
    $now  = new DateTimeImmutable('now');
    $diff = $now->getTimestamp() - $then->getTimestamp();

    if ($diff < 60) return 'just now';
    if ($diff < 3600) { $m = intdiv($diff, 60); return $m . ' minute' . ($m === 1 ? '' : 's') . ' ago'; }
    if ($diff < 86400) { $h = intdiv($diff, 3600); return $h . ' hour' . ($h === 1 ? '' : 's') . ' ago'; }
    $d = intdiv($diff, 86400);
    if ($d < 7) return $d . ' day' . ($d === 1 ? '' : 's') . ' ago';
    return $then->format('M j, Y');
}

/**
 * Recent activity across a teacher's whole roster (their pupils + any
 * legacy/unassigned ones — same ownership rule as bulig_level1_roster()),
 * newest first. Powers the "recent activity" list on the teacher
 * dashboard and the Activities page.
 */
function bulig_teacher_recent_activity(PDO $pdo, int $teacherPk, int $limit = 8): array
{
    $stmt = $pdo->prepare(
        "SELECT pp.level_id, pp.lesson_id, pp.xp_earned, pp.completed_at,
                p.first_name, p.last_name
         FROM pupil_progress pp
         JOIN pupils p ON p.id = pp.pupil_id
         WHERE p.teacher_id = :tid OR p.teacher_id IS NULL
         ORDER BY pp.completed_at DESC
         LIMIT " . max(1, $limit)
    );
    $stmt->execute(['tid' => $teacherPk]);
    $rows = $stmt->fetchAll();

    $icons = [1 => '🚀', 2 => '🔤', 3 => '🔡', 4 => '📖', 5 => '🎙️', 6 => '👂'];
    $out = [];
    foreach ($rows as $r) {
        $levelId = (int) $r['level_id'];
        $lessonId = (int) $r['lesson_id'];
        $what = $lessonId === 0 ? 'Pre-Assessment' : ($lessonId === 100 ? 'Post-Assessment' : 'Lesson ' . $lessonId);
        $name = trim($r['first_name'] . ' ' . $r['last_name']);
        $out[] = [
            'icon' => $icons[$levelId] ?? '⭐',
            'text' => $name . ' finished ' . bulig_level_label($levelId) . ' — ' . $what,
            'xp'   => (int) $r['xp_earned'],
            'when' => bulig_time_ago((string) $r['completed_at']),
        ];
    }
    return $out;
}

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

/**
 * The full pupil roster a teacher can see: every pupil they added, plus
 * any "legacy/unassigned" pupil (teacher_id IS NULL, e.g. added before
 * teacher accounts existed) — see sql/teacher_admin.sql. Each row is
 * merged with that pupil's Level 1 progress, since Level 1 status is what
 * the Teacher Dashboard, My Pupils, and Progress pages all key off of.
 *
 * Previously called from teacher/dashboard.php, teacher/pupils.php, and
 * teacher/progress.php but never defined anywhere — every one of those
 * pages fatally errored on load. See BULIG_FIXES.md.
 *
 * @return list<array{id:int, student_id:string, name:string,
 *   teacher_id:?int, grade_level:int, current_level:int, xp:int,
 *   completed:int[], lessonsTotal:int, preDone:bool, postDone:bool,
 *   preAnswers:object, postAnswers:object, streakDays:int, status:string}>
 */
function bulig_level1_roster(PDO $pdo, int $teacherPk): array
{
    $stmt = $pdo->prepare(
        'SELECT id, student_id, first_name, last_name, teacher_id, grade_level, current_level
         FROM pupils
         WHERE teacher_id = :tid OR teacher_id IS NULL
         ORDER BY last_name, first_name'
    );
    $stmt->execute(['tid' => $teacherPk]);

    $roster = [];
    foreach ($stmt->fetchAll() as $row) {
        $pupilId = (int) $row['id'];
        $summary = bulig_level1_summary($pdo, $pupilId);
        $doneCount = count($summary['completed']);

        if ($doneCount === 0) {
            $status = 'not_started';
        } elseif ($doneCount < $summary['lessonsTotal']) {
            $status = 'in_progress';
        } elseif ($summary['postDone']) {
            $status = 'champion';
        } else {
            $status = 'completed';
        }

        $roster[] = [
            'id'            => $pupilId,
            'student_id'    => (string) $row['student_id'],
            'name'          => trim($row['first_name'] . ' ' . $row['last_name']),
            'teacher_id'    => $row['teacher_id'] !== null ? (int) $row['teacher_id'] : null,
            'grade_level'   => (int) $row['grade_level'],
            'current_level' => (int) $row['current_level'],
            'xp'            => $summary['xp'],
            'completed'     => $summary['completed'],
            'lessonsTotal'  => $summary['lessonsTotal'],
            'preDone'       => $summary['preDone'],
            'postDone'      => $summary['postDone'],
            'preAnswers'    => $summary['preAnswers'],
            'postAnswers'   => $summary['postAnswers'],
            'streakDays'    => $summary['streakDays'],
            'status'        => $status,
        ];
    }
    return $roster;
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
