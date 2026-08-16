USE nsp;

CREATE TABLE IF NOT EXISTS job_application_sequences (
    id TINYINT UNSIGNED PRIMARY KEY,
    next_number INT UNSIGNED NOT NULL DEFAULT 0
);

INSERT IGNORE INTO job_application_sequences (id, next_number) VALUES (1, 0);

ALTER TABLE job_applications
    ADD COLUMN application_number VARCHAR(20) NULL UNIQUE AFTER id;

UPDATE job_applications
SET application_number = CONCAT(
    UPPER(LEFT(position_applied, 3)),
    LPAD(id, 6, '0'),
    YEAR(created_at)
)
WHERE application_number IS NULL;

UPDATE job_application_sequences
SET next_number = GREATEST(next_number, COALESCE((SELECT MAX(id) FROM job_applications), 0))
WHERE id = 1;

ALTER TABLE job_applications
    MODIFY application_number VARCHAR(20) NOT NULL;