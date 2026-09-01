-- BULIG — Teacher accounts, pupil ownership, level assignment, and Admin role
-- Run this once against bulig_db. If a statement errors with "Duplicate
-- column name" or "Table already exists", that piece was already applied —
-- just skip it and run the rest.

-- Each pupil can be "owned" by the teacher who added them. Existing pupils
-- (added before this migration) get teacher_id = NULL, meaning
-- "unassigned / legacy" — every teacher can still see and manage them, so
-- nothing that already worked stops working.
ALTER TABLE pupils
    ADD COLUMN teacher_id INT UNSIGNED NULL AFTER id;

ALTER TABLE pupils
    ADD CONSTRAINT fk_pupil_teacher FOREIGN KEY (teacher_id)
        REFERENCES teachers(id) ON DELETE SET NULL;

-- Which reading level a pupil should start/continue at (1-7). Defaults to
-- 1 so every existing pupil keeps behaving exactly as before.
ALTER TABLE pupils
    ADD COLUMN current_level TINYINT UNSIGNED NOT NULL DEFAULT 1 AFTER grade_level;

-- A separate role table — never mixed into pupils or teachers.
CREATE TABLE IF NOT EXISTS admins (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_id      VARCHAR(20)  NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    first_name    VARCHAR(60)  NOT NULL,
    last_name     VARCHAR(60)  NOT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- DEMO ADMIN ACCOUNT — for local testing only.
--   Admin ID: A2026001      Password: admin123
-- Delete or change this before any real deployment.
-- ---------------------------------------------------------------------
INSERT INTO admins (admin_id, password_hash, first_name, last_name) VALUES
('A2026001', '$2b$10$aeQrml7pZWY2P0HCZ6aYDeoJlcE1XNJtscNLjUUy70weeFPu.OZcK', 'System', 'Admin');
