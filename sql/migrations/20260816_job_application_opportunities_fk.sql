USE nsp;

ALTER TABLE job_applications
    ADD COLUMN opportunities_fk INT UNSIGNED NULL AFTER application_number;

UPDATE job_applications ja
INNER JOIN opportunities o ON o.name_of_post = ja.position_applied
SET ja.opportunities_fk = o.id
WHERE ja.opportunities_fk IS NULL;

ALTER TABLE job_applications
    ADD CONSTRAINT fk_job_applications_opportunity
        FOREIGN KEY (opportunities_fk) REFERENCES opportunities(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT;