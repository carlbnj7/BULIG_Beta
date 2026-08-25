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
│   └── session.php          require_pupil_login() / require_teacher_login()
├── partials/                 Shared includes (sidebars, head, mobile topbar, coming-soon)
├── css/style.css
├── js/login.js               Tab switching, password show/hide, numeric-ID filter
├── js/sidebar.js              Mobile sidebar open/close
├── assets/
│   ├── bulig-logo.png        (unchanged — do not replace)
│   └── footer.png            (unchanged — do not replace)
├── pupil/
│   ├── dashboard.php         Home — XP/badges/streak, quest shortcuts
│   ├── lessons.php           My Lessons — Level 1 + locked future levels
│   ├── level1.php            Level 1 quest holding page
│   ├── activities.php        Activities (honest "coming soon")
│   ├── achievements.php      Badge grid (locked until earned)
│   ├── progress.php          XP + lesson-completion bars
│   └── profile.php           Real account details
├── teacher/
│   ├── dashboard.php         Dashboard — real pupil count + quick actions
│   ├── pupils.php            Real pupil roster (live query, not a mockup)
│   ├── materials.php         Learning Materials ("coming soon")
│   ├── activities.php        Activities ("coming soon")
│   ├── progress.php          Progress reports ("coming soon")
│   ├── assignments.php       Assignments ("coming soon")
│   └── profile.php           Real account details
└── sql/schema.sql           `pupils` + `teachers` tables, demo accounts
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

Level 1's actual 12-lesson quest (EXP, badges, text-to-speech) plugs into
`pupil/level1.php` next.
