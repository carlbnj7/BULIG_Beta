# BULIG — Level 4 Integration & System Fix Notes

This documents every bug fixed and every file added/changed while integrating
Level 4 (Reading Fluency, Grades 1-6) into the existing Levels 1-3 system.

## 1. Pre-existing bugs fixed (Teacher & Pupil Dashboards)

These were found by tracing every page that errored, not just the ones the
report mentioned — all four were **fatal PHP errors** that completely broke
the pages they were on.

1. **`BULIG_MAX_LEVEL` was used but never defined.**
   Used in `teacher/assign_level.php`, `teacher/add_pupil.php`, and
   `teacher/pupils.php` — every one of those crashed with "Undefined
   constant" (PHP 8+ throws a fatal `Error` for this, not just a warning).
   Fixed by defining it in `config/level1_helpers.php`:
   `const BULIG_MAX_LEVEL = 5;` (1=Level 1, 2=Level 2A, 3=Level 2B,
   4=Level 3, 5=Level 4).

2. **`bulig_level1_roster()` was called but never defined.**
   Called from `teacher/dashboard.php`, `teacher/pupils.php`, and
   `teacher/progress.php` — **the entire Teacher Dashboard was broken.**
   Implemented in `config/level1_helpers.php`: builds the teacher's full
   pupil roster (their own pupils + legacy/unassigned ones) merged with
   each pupil's Level 1 progress, status chip, XP, etc.

3. **`bulig_level3_is_complete()` was called but never defined.**
   Called from `pupil/progress.php` — that page fatally errored on load.
   Implemented in `config/level3_helpers.php`.

4. **`bulig_level2a_is_complete()` — called a function that never
   existed (typo for the actual `bulig_level2_is_complete()`).**
   Also in `pupil/progress.php`. Fixed to call the real function name.

## 2. Critical data-integrity bug: Level 2B / Level 3 `level_id` collision

`config/level2b_helpers.php` used `level_id = 3`. `config/level3_helpers.php`
**also** used `level_id = 3`. Both write to the same `pupil_progress` table
under the same `UNIQUE KEY (pupil_id, level_id, lesson_id)` — so for any
pupil who did both levels, their rows collided and silently overwrote each
other's XP/completion data.

**Fix:** Level 3 now uses `level_id = 4` (`BULIG_L3_LEVEL_ID` in
`config/level3_helpers.php`). Level 4 (new) takes the next free slot,
`level_id = 5` (`BULIG_L4_LEVEL_ID` in `config/level4_helpers.php`).

Final numbering: **1 = Level 1, 2 = Level 2A, 3 = Level 2B, 4 = Level 3,
5 = Level 4.**

`pupil/level3_save.php` was also fixed — it hardcoded the literal `3`
instead of using the constant, so the renumbering had to be applied there
by hand too.

**If you already have real pupil progress saved under the old
`level_id = 3`,** see the migration note in `sql/level3_progress.sql` —
it explains exactly which rows are safe to move automatically and which
need manual review (short version: `lesson_id` 7-25 can only ever be
Level 3 and are safe to bulk-move; `lesson_id` 0/100/1-6 are ambiguous
between the two levels and need a per-pupil check).

## 3. Incomplete pupil-facing pages (dead code)

`pupil/progress.php` and `pupil/achievements.php` both **computed** Level 2B
and Level 3 stats/badges in PHP but never actually **rendered** the matching
HTML sections — so even once the fatal errors above were fixed, pupils still
couldn't see that progress. Both pages now render a full section per level
(1, 2A, 2B, 3, and the new 4), each gated behind the previous level's
completion, matching the pattern already used for Level 1 → 2A.

`pupil/dashboard.php`'s badge counter also under-counted — it summed
Level 1 + 2A + 3 but forgot Level 2B. Fixed to include every level.

## 4. Level 4 (Reading Fluency, Grades 1-6) — new

- **Content**: transcribed directly from the six
  `LEVEL-4-FLUENCY-GRADE-{1..6}.pdf` module booklets into
  `config/level4_content.php` (single source of truth for both the pupil
  quest map and the teacher scoring form). Every grade's Pre-test,
  intervention (practice), and Post-test passages are included in full,
  in the order the module's own Table of Contents lists them. See the
  doc comment at the top of that file for the two source-document
  inconsistencies found and how they were handled (nothing was invented
  to paper over them — same policy as the Level 2B Activity-23 TOC
  mismatch handled previously).
- **Grade-gating**: pupils only ever see their own `pupils.grade_level`'s
  passages (`config/level4_helpers.php` → `bulig_pupil_grade()`, always
  read fresh from the database, never trusted from session). A teacher
  sets/changes this on `teacher/pupils.php` via the new
  `teacher/assign_grade.php` handler — mirrors the existing
  `assign_level.php` pattern exactly (ownership check, flash messages).
- **Scoring**: fluency is inherently a teacher-administered, ear-graded
  skill (the module's own paper "Marking and Scoring Guide" — miscue
  tally + reading time → Oral Reading Score / Reading Level / WPM), so:
  - Intervention passages are pupil-paced: the pupil listens (TTS "Read
    Aloud"), reads along, and marks each one practiced for XP — same
    gamification pattern as every other level (`pupil/level4.php`,
    `pupil/level4_save.php`, `js/level4.js`).
  - Pre-/Post-test scores are entered by the **teacher** on
    `teacher/progress.php`'s new "Level 4 Fluency Scoring" section, via
    `teacher/save_fluency_score.php`. `bulig_l4_compute_score()` in
    `config/level4_helpers.php` turns the raw miscue tally into the same
    numbers the paper guide produces by hand. The pupil-side save
    endpoint deliberately refuses to accept a pre/post score itself, so
    a pupil can never self-score their own fluency test from the browser.
- **No schema changes** — reuses the existing level-agnostic
  `pupil_progress` table (`level_id = 5`) and the existing
  `pupils.grade_level` column. See `sql/level4_progress.sql`.
- **Gamification**: same XP system, same badge-grid pattern
  (`pupil/achievements.php`), same `l1-scope` CSS / quest-map visuals,
  same Read Aloud (Web Speech API) mechanism as every other level — no
  new UI framework or styling was introduced.

## 5. Additional hardening found during final review

- **Level-picker mislabeling**: the "Level" dropdown/chip on `teacher/pupils.php`
  and `teacher/progress.php` showed plain "Level 1"/"Level 2"/"Level 3"/etc.
  for the informational `pupils.current_level` field — but those numbers
  are the internal `level_id` scheme (1=Level 1, 2=Level 2A, 3=Level 2B,
  4=Level 3, 5=Level 4), not the pupil-facing level names. A teacher
  picking "Level 3" from that dropdown was actually tagging a pupil as
  Level 2B, with no way to know it. Added `bulig_level_label()` in
  `config/level1_helpers.php` and used it everywhere `current_level` is
  displayed, so the picker/chip now correctly reads "Level 1", "Level 2A",
  "Level 2B", "Level 3", "Level 4". (At the time this was fixed, this
  field was informational/tracking-only and didn't gate access anywhere.
  Section 8.1 below changed that -- it's exactly why getting the labels
  right here matters: a teacher now needs to know precisely which level
  they're placing a pupil into, since it takes effect immediately.)
- **`add_pupil.php`** validated `grade_level >= 1` but never checked the
  upper bound; a crafted request could store an out-of-range grade.
  Tightened to `1-6` to match the dropdown and `assign_grade.php`'s own
  validation.

## 6. Full System Review — assigned-level gating, profile pictures, admin CRUD

This second pass covered: the teacher-assigned starting level not
actually controlling access, profile pictures for every role, and
finishing every stub page (pupil/teacher/admin) with real,
database-backed functionality.

### 6.1 Assigned starting level now actually gates access

Previously `pupils.current_level` (the "Starting Level"/"Level" dropdown
on Teacher → My Pupils) was **purely informational** — it was never
read anywhere that decides what a pupil can actually open. A pupil a
teacher placed directly into Level 3 still had to grind through
1 → 2A → 2B first, same as anyone else, because none of the level
entry-point files or dashboard/lessons/progress/achievements pages ever
checked it.

Fixed with two new helpers in `config/level1_helpers.php`:
- `bulig_pupil_current_level()` — reads `current_level` fresh from the
  DB (never session — a teacher can change it any time).
- `bulig_level_unlocked($levelId, $previousLevelComplete, $assignedLevel)`
  — a level is open if the level before it is genuinely finished **or**
  the pupil's assigned level is at or past it.

Applied everywhere a level's lock state is decided: the four level
entry-point gate checks (`pupil/level2.php`, `level2b.php`, `level3.php`,
`level4.php` — each now redirects back to My Lessons only if truly
locked) and all four pages that render the level cards/sections
(`dashboard.php`, `lessons.php`, `progress.php`, `achievements.php`).
Level 4 keeps its separate Grade requirement regardless of assigned
level, since that gate is about which content exists, not progression.

### 6.2 Profile pictures (pupils, teachers, admins)

- **`sql/profile_pictures.sql`** — adds `avatar_file` to `pupils`,
  `teachers`, `admins`. ⚠️ Required before deploying this update — see
  the warning at the top of that file, login now selects this column.
- **`config/avatar_helpers.php`** — `bulig_avatar_html()` (photo if one
  exists, otherwise the existing letter-initial badge — nothing breaks
  for accounts without a photo) and `bulig_save_avatar_upload()`, which
  validates real image bytes with `getimagesize()` (never trusts the
  filename or declared type), caps size/dimensions, and always
  re-encodes to a fresh JPEG via GD — **requires the PHP GD extension**
  to be enabled on the server.
- `uploads/avatars/` — new folder, guarded with `.htaccess` +
  `index.php` so nothing placed there can ever execute as a script.
- Three new upload handlers: `pupil/upload_avatar.php`,
  `teacher/upload_avatar.php`, `admin/upload_avatar.php`.
- All three login handlers now load `avatar_file` into
  `$_SESSION['avatar_file']`; the upload handlers refresh it
  immediately after a successful upload (no re-login needed to see it).
- Rebuilt `pupil/profile.php` and `teacher/profile.php`, added
  `admin/profile.php` (this also fixes a dead link — the admin sidebar
  gained a Profile nav item pointing at a page that didn't exist yet
  partway through this build).
- All three sidebars now show the uploaded photo when one exists.

### 6.3 Avatar + recent activity on the dashboards

New helpers in `config/level1_helpers.php`: `bulig_pupil_recent_activity()`
and `bulig_teacher_recent_activity()` (class-wide, scoped to the
teacher's roster) read straight off `pupil_progress`, newest first, with
a small `bulig_time_ago()` formatter ("3 days ago"). Both
`pupil/dashboard.php` and `teacher/dashboard.php` now show a circular
avatar beside the person's name (`.who-you-are` block) with a recent
activity list underneath (`.recent-activity-list`) — new CSS only, no
existing styles changed.

### 6.4 Every stub page now has real content

- **`pupil/activities.php`** — personal activity log (every completed
  lesson across every level, total count + total XP).
- **`teacher/activities.php`** — the same feed, class-wide.
- **`teacher/materials.php`** — a curriculum reference (what's in each
  level, plus Level 4's per-grade passage counts) pulled from the real
  lesson-count constants and `bulig_level4_content()`, not hardcoded text.
- **`teacher/assignments.php`** — an assigned-level/grade overview table
  for the whole roster with a per-level summary strip; editing still
  happens on My Pupils (kept in one place rather than duplicating the
  edit form) but this gives a fast way to audit who's assigned where.
- **`admin/dashboard.php`** — swapped the placeholder "Preview / Admin
  Tools" stat card for a real system-wide activity count, and the two
  Quick Links cards from "Coming Soon" to actually working links.
- **`admin/teachers.php`** — full teacher roster with pupil counts, an
  **Add Teacher** form (`admin/add_teacher.php`), and **Remove**
  (`admin/remove_teacher.php`). This also closes a real gap: there was
  previously no way to create a teacher account at all —
  `teacher_register.php` is intentionally disabled (no self sign-up).
- **`admin/pupils.php`** — system-wide pupil roster with a **Reassign
  Teacher** dropdown per pupil (`admin/reassign_teacher.php` — an
  admin-only capability; teachers can't hand a pupil to another teacher
  themselves) and **Remove** (`admin/remove_pupil.php`). Removing a
  teacher does not delete their pupils — `fk_pupil_teacher` is
  `ON DELETE SET NULL`, so those pupils just become unassigned/legacy,
  same as pupils added before the ownership feature existed.

### 6.5 Small hardening while in these files

`teacher/add_pupil.php`'s grade validation only checked `>= 1`; the
admin/pupil-reassignment work above prompted a re-check and it's now
`1-6` to match every other grade validator in the app.

## 7. Pipeline verified end-to-end

Teacher sets a pupil's Grade on **My Pupils** (`assign_grade.php`) → pupil
finishes Levels 1→ 2A → 2B → 3 → **Level 4 unlocks showing only their
grade's passages** (`level4.php`, grade read fresh from DB) → pupil
practices passages for XP and reads the Pre-/Post-test to their teacher →
**teacher records the score** on **Progress** (`save_fluency_score.php`) →
score appears back on the pupil's own **Progress**/**Achievements** pages
and on the teacher's **Progress** table — every step in the requested
"Teacher → Grade Assignment → Pupil → Level 4 → Progress/Score → Teacher
Monitoring" chain is wired and testable.
