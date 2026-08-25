-- BULIG — Bukidnon's Unified Literacy and Intervention Gateway
-- Database schema + demo login accounts.
-- Import with: mysql -u root -p < schema.sql

CREATE DATABASE IF NOT EXISTS bulig_db
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

    USE bulig_db;

    -- Student IDs are numbers only (no hyphens/letters), e.g. 20232223
    CREATE TABLE IF NOT EXISTS pupils (
        id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            student_id    VARCHAR(15)  NOT NULL UNIQUE,
                password_hash VARCHAR(255) NOT NULL,
                    first_name    VARCHAR(60)  NOT NULL,
                        last_name     VARCHAR(60)  NOT NULL,
                            grade_level   TINYINT UNSIGNED NOT NULL,
                                section       VARCHAR(40)  NULL,
                                    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                        CONSTRAINT chk_student_id_numeric CHECK (student_id REGEXP '^[0-9]+$')
                                        ) ENGINE=InnoDB;

                                        -- Teacher IDs may use their own format (letters + numbers are fine here)
                                        CREATE TABLE IF NOT EXISTS teachers (
                                            id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                                teacher_id    VARCHAR(20)  NOT NULL UNIQUE,
                                                    password_hash VARCHAR(255) NOT NULL,
                                                        first_name    VARCHAR(60)  NOT NULL,
                                                            last_name     VARCHAR(60)  NOT NULL,
                                                                created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                                                                ) ENGINE=InnoDB;

                                                                -- ---------------------------------------------------------------------
                                                                -- DEMO ACCOUNTS — ready to log in with, for local testing only.
                                                                -- Passwords are already bcrypt-hashed (exactly what PHP's password_hash()
                                                                -- produces) — the database never stores a plain-text password.
                                                                --
                                                                --   PUPIL LOGIN
                                                                --     Student ID: 20232223      Password: pupil123
                                                                --     Student ID: 20232224      Password: pupil123
                                                                --
                                                                --   TEACHER LOGIN
                                                                --     Teacher ID: T2026045      Password: teacher123
                                                                --
                                                                -- Delete these three INSERTs (or change the passwords) before using this
                                                                -- for an actual school deployment.
                                                                -- ---------------------------------------------------------------------

                                                                INSERT INTO pupils (student_id, password_hash, first_name, last_name, grade_level, section) VALUES
                                                                ('20232223', '$2b$10$Eo/RXSBCw8m2cNlMNinNJOXI.ptoenj1UT9q4NNZt24KE9KR95VyC', 'Juan',  'Dela Cruz', 1, 'Sampaguita'),
                                                                ('20232224', '$2b$10$OxviykkaPzXJXB4F9WKISuBi9qG2aH.57TyXcjs4FtNjHSydmEYWm', 'Maria', 'Reyes',     1, 'Sampaguita');

                                                                INSERT INTO teachers (teacher_id, password_hash, first_name, last_name) VALUES
                                                                ('T2026045', '$2b$10$EIVt/ybqR.gB56x4OD2VZ.kGMUMx/fV5Rxq6Uhm7/89fqmEUus65C', 'Ana', 'Santos');

                                                                -- ---------------------------------------------------------------------
                                                                -- To add more accounts later, generate a fresh hash with PHP:
                                                                --
                                                                --   php -r "echo password_hash('YourNewPassword', PASSWORD_DEFAULT);"
                                                                --
                                                                -- then insert a new row using that hash, e.g.:
                                                                --
                                                                --   INSERT INTO pupils (student_id, password_hash, first_name, last_name, grade_level, section)
                                                                --   VALUES ('20232225', '$2y$10$PASTE_NEW_HASH_HERE', 'Pedro', 'Garcia', 1, 'Sampaguita');
                                                                -- ---------------------------------------------------------------------
                                                                