USE nsp;

ALTER TABLE job_applications
    ADD COLUMN generated_resume_path VARCHAR(255) AFTER signature_path;