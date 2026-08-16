USE nsp;

ALTER TABLE job_applications
    ADD COLUMN secondary_guardian_nominee_name VARCHAR(150) NULL AFTER generated_resume_path,
    ADD COLUMN secondary_branch_course VARCHAR(150) NULL AFTER secondary_guardian_nominee_name,
    ADD COLUMN secondary_subject_aadhaar VARCHAR(150) NULL AFTER secondary_branch_course,
    ADD COLUMN secondary_area_of_learning VARCHAR(200) NULL AFTER secondary_subject_aadhaar,
    ADD COLUMN secondary_percentage_cgpa VARCHAR(20) NULL AFTER secondary_area_of_learning,
    ADD COLUMN secondary_passport_number VARCHAR(50) NULL AFTER secondary_percentage_cgpa,
    ADD COLUMN secondary_certificate_path VARCHAR(255) NULL AFTER secondary_passport_number;
