USE nsp;

ALTER TABLE job_applications
    ADD COLUMN email VARCHAR(150) NULL AFTER date_of_birth,
    ADD COLUMN mobile_no VARCHAR(20) NULL AFTER email,
    ADD COLUMN marital_status VARCHAR(50) NULL AFTER mobile_no,
    ADD COLUMN reference1_email VARCHAR(150) NULL AFTER reference1_phone,
    ADD COLUMN reference2_email VARCHAR(150) NULL AFTER reference2_phone;
