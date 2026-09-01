-- BULIG — Profile picture support for pupils and teachers.
--
-- ⚠️ REQUIRED before deploying this update — unlike some earlier
-- migrations, this one is not "run it whenever you get to that feature":
-- the login handlers (pupil_login.php, teacher_login.php, admin_login.php)
-- now SELECT the `avatar_file` column as part of every sign-in. If you
-- deploy the new PHP files without running this migration first, sign-in
-- will fail for everyone (gracefully, with a "Something went wrong"
-- message — PDO is in exception mode — but nobody will be able to log in
-- until this is applied). Run this before/at the same time as the code
-- update, not after.
--
-- Run this once against bulig_db, same rule as teacher_admin.sql: if a
-- statement errors with "Duplicate column name", that piece was already
-- applied — skip it and run the rest.
--
-- Stores only the filename (not the full path) of an uploaded avatar,
-- e.g. 'pupil_7_1699999999.jpg'. The actual image files live on disk at
-- /uploads/avatars/, served directly by the web server — never anything
-- that touches PHP execution (no .php uploads are ever accepted, see
-- pupil/upload_avatar.php / teacher/upload_avatar.php for the
-- allow-list + re-encode step that enforces this).
-- NULL means "no photo uploaded yet" — every page that shows an avatar
-- already has a letter-initial fallback for this case, so nothing
-- breaks for existing accounts.

ALTER TABLE pupils
    ADD COLUMN avatar_file VARCHAR(120) NULL AFTER section;

ALTER TABLE teachers
    ADD COLUMN avatar_file VARCHAR(120) NULL AFTER last_name;

-- Admins can have one too (same upload UI is reused on admin/profile.php).
ALTER TABLE admins
    ADD COLUMN avatar_file VARCHAR(120) NULL AFTER last_name;
