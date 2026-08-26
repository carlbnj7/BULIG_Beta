-- BULIG — Level 1 Oral Language progress table
-- Fixed: pupil_id is INT UNSIGNED to exactly match pupils.id's type
-- (MySQL requires matching types/signedness for a foreign key).

CREATE TABLE IF NOT EXISTS pupil_progress (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pupil_id      INT UNSIGNED NOT NULL,
    level_id      INT NOT NULL DEFAULT 1,
    -- lesson_id: 1-12 = quest lessons, 0 = pre-assessment, 100 = post-assessment
    lesson_id     INT NOT NULL,
    xp_earned     INT NOT NULL DEFAULT 0,
    answer_text   TEXT NULL,               -- JSON blob of typed answers (assessments only)
    completed_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_pupil_level_lesson (pupil_id, level_id, lesson_id),
    CONSTRAINT fk_progress_pupil FOREIGN KEY (pupil_id) REFERENCES pupils(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
