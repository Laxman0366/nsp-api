USE nsp;

CREATE TABLE IF NOT EXISTS job_application_sequences (
    id TINYINT UNSIGNED PRIMARY KEY,
    next_number INT UNSIGNED NOT NULL DEFAULT 0
);

INSERT IGNORE INTO job_application_sequences (id, next_number) VALUES (1, 0);

DELIMITER $$

DROP PROCEDURE IF EXISTS add_job_application_column_if_missing$$
CREATE PROCEDURE add_job_application_column_if_missing(
    IN p_column_name VARCHAR(64),
    IN p_column_definition VARCHAR(255)
)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns AS c
        WHERE table_schema = DATABASE()
          AND table_name = 'job_applications'
          AND c.column_name = p_column_name
    ) THEN
        SET @sql = CONCAT(
            'ALTER TABLE job_applications ADD COLUMN ',
            p_column_name,
            ' ',
            p_column_definition
        );
        PREPARE statement FROM @sql;
        EXECUTE statement;
        DEALLOCATE PREPARE statement;
    END IF;
END$$

CALL add_job_application_column_if_missing('application_number', 'VARCHAR(20) NULL UNIQUE AFTER id')$$
CALL add_job_application_column_if_missing('generated_resume_path', 'VARCHAR(255) NULL AFTER signature_path')$$
CALL add_job_application_column_if_missing('secondary_qualification', 'VARCHAR(200) NULL')$$
CALL add_job_application_column_if_missing('secondary_university', 'VARCHAR(200) NULL')$$
CALL add_job_application_column_if_missing('secondary_specialisation', 'VARCHAR(200) NULL')$$
CALL add_job_application_column_if_missing('secondary_passing_year', 'VARCHAR(20) NULL')$$
CALL add_job_application_column_if_missing('secondary_percentage', 'VARCHAR(20) NULL')$$
CALL add_job_application_column_if_missing('secondary_passing_category', 'VARCHAR(200) NULL')$$
CALL add_job_application_column_if_missing('higher_secondary_qualification', 'VARCHAR(200) NULL')$$
CALL add_job_application_column_if_missing('higher_secondary_university', 'VARCHAR(200) NULL')$$
CALL add_job_application_column_if_missing('higher_secondary_specialisation', 'VARCHAR(200) NULL')$$
CALL add_job_application_column_if_missing('higher_secondary_passing_year', 'VARCHAR(20) NULL')$$
CALL add_job_application_column_if_missing('higher_secondary_percentage', 'VARCHAR(20) NULL')$$
CALL add_job_application_column_if_missing('higher_secondary_passing_category', 'VARCHAR(200) NULL')$$
CALL add_job_application_column_if_missing('graduation_qualification', 'VARCHAR(200) NULL')$$
CALL add_job_application_column_if_missing('graduation_university', 'VARCHAR(200) NULL')$$
CALL add_job_application_column_if_missing('graduation_specialisation', 'VARCHAR(200) NULL')$$
CALL add_job_application_column_if_missing('graduation_passing_year', 'VARCHAR(20) NULL')$$
CALL add_job_application_column_if_missing('graduation_percentage', 'VARCHAR(20) NULL')$$
CALL add_job_application_column_if_missing('graduation_passing_category', 'VARCHAR(200) NULL')$$
CALL add_job_application_column_if_missing('post_graduation_qualification', 'VARCHAR(200) NULL')$$
CALL add_job_application_column_if_missing('post_graduation_university', 'VARCHAR(200) NULL')$$
CALL add_job_application_column_if_missing('post_graduation_specialisation', 'VARCHAR(200) NULL')$$
CALL add_job_application_column_if_missing('post_graduation_passing_year', 'VARCHAR(20) NULL')$$
CALL add_job_application_column_if_missing('post_graduation_percentage', 'VARCHAR(20) NULL')$$
CALL add_job_application_column_if_missing('post_graduation_passing_category', 'VARCHAR(200) NULL')$$
CALL add_job_application_column_if_missing('other_qualification', 'VARCHAR(200) NULL')$$
CALL add_job_application_column_if_missing('other_university', 'VARCHAR(200) NULL')$$
CALL add_job_application_column_if_missing('other_specialisation', 'VARCHAR(200) NULL')$$
CALL add_job_application_column_if_missing('other_passing_year', 'VARCHAR(20) NULL')$$
CALL add_job_application_column_if_missing('other_percentage', 'VARCHAR(20) NULL')$$
CALL add_job_application_column_if_missing('other_passing_category', 'VARCHAR(200) NULL')$$

DROP PROCEDURE add_job_application_column_if_missing$$

DELIMITER ;

UPDATE job_applications
SET application_number = CONCAT(
    UPPER(LEFT(position_applied, 3)),
    LPAD(id, 6, '0'),
    YEAR(created_at)
)
WHERE application_number IS NULL OR application_number = '';

ALTER TABLE job_applications
    MODIFY application_number VARCHAR(20) NOT NULL;

SET @register_year_exists = (
    SELECT COUNT(*)
    FROM information_schema.columns
    WHERE table_schema = DATABASE()
      AND table_name = 'job_applications'
      AND column_name = 'register_year'
);

SET @drop_register_year_sql = IF(
    @register_year_exists > 0,
    'ALTER TABLE job_applications DROP COLUMN register_year',
    'SELECT 1'
);
PREPARE drop_register_year_statement FROM @drop_register_year_sql;
EXECUTE drop_register_year_statement;
DEALLOCATE PREPARE drop_register_year_statement;

UPDATE job_application_sequences
SET next_number = GREATEST(next_number, COALESCE((SELECT MAX(id) FROM job_applications), 0))
WHERE id = 1;
