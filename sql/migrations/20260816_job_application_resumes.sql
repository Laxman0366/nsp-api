USE nsp;

CREATE TABLE IF NOT EXISTS job_application_resumes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_applications_fk INT UNSIGNED NOT NULL,
    generated_resume_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_job_application_resumes_job_application
        FOREIGN KEY (job_applications_fk) REFERENCES job_applications(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

INSERT INTO job_application_resumes (job_applications_fk, generated_resume_path)
SELECT id, generated_resume_path
FROM job_applications
WHERE generated_resume_path IS NOT NULL AND generated_resume_path <> '';

ALTER TABLE job_applications
    DROP COLUMN generated_resume_path;
