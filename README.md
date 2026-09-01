# BULIG — Capstone 2 System

Bukidnon's Unified Literacy and Intervention Gateway — Login + Pupil & Teacher homepages.

## File structure

```
BULIG/
├── index.php                Login page (Pupil / Teacher tabs)
├── pupil_login.php          Verifies numeric Student ID + password
├── teacher_login.php        Verifies Teacher ID + password
├── logout.php
├── config/
│   ├── database.php         PDO connection (edit DB_* constants or env vars)
│   ├── session.php          require_pupil_login() / require_teacher_login()
│   ├── level1_helpers.php   Level 1 XP/lesson-count constants + progress summary
│   └── level2_helpers.php   Level 2A XP/lesson-count constants + progress summary
├── partials/                 Shared includes (sidebars, head, mobile topbar, coming-soon)
├── css/
│   ├── style.css             Global design system (colors, cards, stat/action grids, badges)
│   └── level1.css            Quest-map + lesson-overlay components — shared by Level 1 AND Level 2A
├── js/login.js               Tab switching, password show/hide, numeric-ID filter
├── js/sidebar.js              Mobile sidebar open/close
├── js/level1.js               Level 1 quest engine (12 Oral Language lessons)
├── js/level2.js               Level 2A quest engine (8 Phonological Awareness lessons)
├── assets/
│   ├── bulig-logo.png        (unchanged — do not replace)
│   └── footer.png            (unchanged — do not replace)
├── pupil/
│   ├── dashboard.php         Home — XP/badges/streak, quest shortcuts (Level 1 + Level 2A)
│   ├── lessons.php           My Lessons — Level 1, Level 2A (unlocks after Level 1), locked 3-4
│   ├── level1.php            Level 1 quest map + lessons
│   ├── level1_save.php       Level 1 progress writer (AJAX target for js/level1.js)
│   ├── level2.php            Level 2A quest map + lessons (locked until Level 1 is 100% done)
│   ├── level2_save.php       Level 2A progress writer (AJAX target for js/level2.js)
│   ├── activities.php        Activities (honest "coming soon")
│   ├── achievements.php      Badge grid — Level 1 badges + Level 2A badges
│   ├── progress.php          XP + lesson-completion bars — Level 1 + Level 2A
│   └── profile.php           Real account details
├── teacher/
│   ├── dashboard.php         Dashboard — real pupil count + quick actions
│   ├── pupils.php            Real pupil roster (live query, not a mockup)
│   ├── materials.php         Learning Materials ("coming soon")
│   ├── activities.php        Activities ("coming soon")
│   ├── progress.php          Progress reports ("coming soon")
│   ├── assignments.php       Assignments ("coming soon")
│   └── profile.php           Real account details
└── sql/
    ├── schema.sql             `pupils` + `teachers` tables, demo accounts
    ├── level1_progress.sql    `pupil_progress` table (shared by every level)
    └── level2_progress.sql    Doc-only note: Level 2A reuses `pupil_progress` via level_id=2
```

## Setup

1. Import the schema: `mysql -u root -p < sql/schema.sql`
2. Edit `config/database.php` with your DB credentials (or set env vars
   `BULIG_DB_HOST`, `BULIG_DB_NAME`, `BULIG_DB_USER`, `BULIG_DB_PASS`).
3. Run: `php -S localhost:8000` from the `BULIG/` folder, then open
   `http://localhost:8000/`.

## Demo accounts (included in `sql/schema.sql`)

| Role    | ID       | Password    |
|---------|----------|-------------|
| Pupil   | 20232223 | pupil123    |
| Pupil   | 20232224 | pupil123    |
| Teacher | T2026045 | teacher123  |

Delete or change these before any real deployment — they're public once shared.

## What changed in this pass

- **Level 2A now covers the ENTIRE 155-page module — all 20 numbered
  Activities**, not just a sample. The module's own structure is:
  8 skill Lessons (Isolation, Identification, Categorization, Blending,
  Segmentation, Deletion, Addition, Substitution), each containing 2-3
  numbered Activities (20 total) plus a Pretest and Posttest. Level 2A's
  quest map mirrors that exactly: **8 unit nodes**, and opening a unit now
  walks the pupil through **every one of its real activities** as
  sequential stages (with a "1 2 3" progress-dot header) before the unit
  is marked complete — e.g. "Sound Detectives" runs Beginning Sounds →
  Middle Sounds → Ending Sounds, matching Activities 1, 2, and 3 exactly.
  - Every word, example, and answer key used is taken directly from the
    module's own worksheets/evaluations (e.g. the real "sky, sun, rabbit"
    categorization item, the real "fam+/r/→farm" addition table, the real
    "bug→bun / mat→man / rat→ran" substitution pairs) — nothing invented.
  - **100% reuses Level 1's existing engines and CSS** — no new UI
    components or styles. The `quiz` and `sentence` engines are unchanged;
    `sequence` gained multi-round support (so one Blending activity can
    walk through several words) using the same pattern the `quiz` engine
    already uses for multiple questions — that's the only new "flow"
    logic added, everything else is content.
  - XP per unit raised to 90 (from 60) since a unit is now 2-3 real
    activities' worth of work; assessments stay 50 XP. Read Aloud, the
    quest map, locking, and celebration screens all work the same way
    they do in Level 1.
  - **Verified with an automated headless-browser walkthrough** (Playwright)
    that plays every question/round/field in all 8 units end-to-end, opens
    the Post-Assessment once unlocked, and submits it — zero JS errors,
    correct XP totals, correct save-endpoint calls.
  - Still **locked until Level 1 is 100% complete**, enforced both in the
    UI and server-side in `level2_save.php`.
  - Still saves to the same `pupil_progress` table via `level_id = 2` —
    no schema changes were needed for this pass either.
- **Level 2A: Phonological Awareness is live**, wired into
  Pupil Dashboard → Level 2 exactly like Level 1's quest system, with
  its own Pre/Post-Assessment, XP, badges (`pupil/achievements.php`) and
  progress bars (`pupil/progress.php`) added below the existing Level 1
  sections without changing them.
- **Student IDs are now numbers only.** The login field strips non-digits as
  you type, and `pupil_login.php` rejects anything that isn't `ctype_digit`
  before it ever touches the database.
- **Login page** keeps the same two-tab Pupil/Teacher flow and the untouched
  logo/footer, with a few more layered educational decorations (clouds,
  letters, stars) behind the card.
- **Pupil homepage** now has a real left sidebar (Home, My Lessons,
  Activities, Achievements, My Progress, Profile, Log Out), a playful
  multi-color background with floating book/star/cloud/letter shapes, and
  animated XP/progress bars. Collapses to a hamburger drawer under 900px.
- **Teacher homepage** has its own sidebar (Dashboard, My Pupils, Learning
  Materials, Activities, Progress, Assignments, Profile, Log Out) on a calm
  gridded background — deliberately less playful than the pupil side.
- **"My Pupils" is a real, working page** — it queries the `pupils` table
  live and lists every registered pupil; it is not a mockup.
- Every sidebar link goes to a real page. Features not built yet (Learning
  Materials, class-wide Progress, Assignments, Activities review) show an
  honest "this is being built" panel instead of a dead or fake button.
- Nothing in `assets/` was touched — same `bulig-logo.png` and `footer.png`.

## Next step

Level 3 plugs into `pupil/lessons.php` / `pupil/dashboard.php` next, once
its own content is ready — it should unlock the same way Level 2A does
(check the previous level's summary via its `_helpers.php`, gate both the
page and its save endpoint).
