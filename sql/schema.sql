CREATE DATABASE IF NOT EXISTS nsp;
USE nsp;

CREATE TABLE IF NOT EXISTS nsp_applicants (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    institute_name VARCHAR(150) NOT NULL,
    course_name VARCHAR(120) NOT NULL,
    scholarship_status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS banners (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    image_path VARCHAR(255) NOT NULL,
    title VARCHAR(200),
    sub_title VARCHAR(200),
    alt_text VARCHAR(200) NULL,
    is_active TINYINT(1) DEFAULT 1,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS contact_details (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mobile VARCHAR(20) NOT NULL,
    email VARCHAR(150) NOT NULL,
    address TEXT NOT NULL,
    youtube_url VARCHAR(255),
    facebook_url VARCHAR(255),
    twitter_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS bank_details (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    account_name VARCHAR(150) NOT NULL,
    account_number VARCHAR(50) NOT NULL,
    ifsc_code VARCHAR(20) NOT NULL,
    bank_name VARCHAR(150) NOT NULL,
    branch_name VARCHAR(150) NOT NULL,
    bank_image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS advertisements (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    title_hindi VARCHAR(200),
    title_odia VARCHAR(200),
    description TEXT,
    description_hindi TEXT,
    description_odia TEXT,
    opening_date VARCHAR(50) NOT NULL,
    closing_date VARCHAR(50),
    posted_by VARCHAR(150),
    detail_file_path VARCHAR(255),
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tender_notices (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    title_hindi VARCHAR(200),
    title_odia VARCHAR(200),
    description TEXT,
    description_hindi TEXT,
    description_odia TEXT,
    opening_date VARCHAR(50) NOT NULL,
    closing_date VARCHAR(50),
    posted_by VARCHAR(150),
    detail_file_path VARCHAR(255),
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS news_events (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    title_hindi VARCHAR(200),
    title_odia VARCHAR(200),
    description TEXT,
    description_hindi TEXT,
    description_odia TEXT,
    posted_by VARCHAR(150),
    detail_file_path VARCHAR(255),
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS partners (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    logo_path VARCHAR(255) NOT NULL,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS milestones (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    count INT NOT NULL DEFAULT 0,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS success_stories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    title_hindi VARCHAR(200),
    title_odia VARCHAR(200),
    beneficiary_name VARCHAR(200) NOT NULL,
    beneficiary_name_hindi VARCHAR(200),
    beneficiary_name_odia VARCHAR(200),
    details TEXT,
    details_hindi TEXT,
    details_odia TEXT,
    image_path VARCHAR(255),
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS media_coverages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    date_time DATETIME,
    image_path VARCHAR(255),
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS awards_recognitions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    date_time DATETIME,
    image_path VARCHAR(255),
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS annual_reports (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS legal_documents (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    document_name VARCHAR(200) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS legal_status (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    status_details TEXT NOT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS audit_reports (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS beneficiary_report (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_name VARCHAR(200) NOT NULL,
    no_of_beneficiaries INT NOT NULL DEFAULT 0,
    file_last_update_datetime DATETIME,
    file_path VARCHAR(255) NOT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS staffs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS food_menu (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS image_gallery (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS video_gallery (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    video_path VARCHAR(255) NOT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS programme_master (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    programme_name VARCHAR(200) NOT NULL,
    programme_name_hindi VARCHAR(200),
    programme_name_odia VARCHAR(200),
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS projects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    programme_master_fk INT UNSIGNED NOT NULL,
    project_name VARCHAR(200) NOT NULL,
    project_name_hindi VARCHAR(200),
    project_name_odia VARCHAR(200),
    project_details TEXT,
    project_details_hindi TEXT,
    project_details_odia TEXT,
    achievement_details TEXT,
    achievement_details_hindi TEXT,
    achievement_details_odia TEXT,
    image_path VARCHAR(255),
    other_image_paths TEXT,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_projects_programme_master
        FOREIGN KEY (programme_master_fk) REFERENCES programme_master(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS programme_overview (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    programme_master_fk INT UNSIGNED NOT NULL,
    projects_fk INT UNSIGNED NOT NULL,
    starting_year YEAR,
    supported_by VARCHAR(255),
    status VARCHAR(100),
    strength VARCHAR(100),
    beneficiaries_covered INT DEFAULT 0,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_programme_overview_programme_master
        FOREIGN KEY (programme_master_fk) REFERENCES programme_master(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT fk_programme_overview_projects
        FOREIGN KEY (projects_fk) REFERENCES projects(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS opportunities (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name_of_post VARCHAR(200) NOT NULL,
    req_qualification TEXT NOT NULL,
    number_of_post INT NOT NULL DEFAULT 0,
    remuneration VARCHAR(150) NOT NULL,
    lower_age INT,
    upper_age INT,
    closing_date DATE,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS cctv_details (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_name VARCHAR(200) NOT NULL,
    project_name_hindi VARCHAR(200),
    project_name_odia VARCHAR(200),
    serial_number VARCHAR(100) NOT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS organization_details (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    phone_number VARCHAR(20) NOT NULL,
    email VARCHAR(150) NOT NULL,
    office_address TEXT NOT NULL,
    office_address_hindi TEXT,
    office_address_odia TEXT,
    facebook_url VARCHAR(255) NULL,
    twitter_url VARCHAR(255) NULL,
    linkedin_url VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS donations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    donor_name VARCHAR(200) NOT NULL,
    donation_amount DECIMAL(12,2) NOT NULL,
    donation_date DATE NOT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS governing_bodies (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    name_hindi VARCHAR(200),
    name_odia VARCHAR(200),
    position VARCHAR(200) NOT NULL,
    qualification VARCHAR(255) NOT NULL,
    image_path VARCHAR(255),
    message TEXT,
    message_hindi TEXT,
    message_odia TEXT,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admin_users (
    id INT UNSIGNED NOT NULL PRIMARY KEY DEFAULT 1,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_admin_users_singleton CHECK (id = 1)
);

-- Default admin login: username "admin", password "Admin@123" (change after first login).
INSERT INTO admin_users (username, password_hash)
VALUES ('admin', '$2y$10$l78EzV6duvdkGAyjsqZWN.TuErWWdcK6/3znEdPdRJE4MAMkfVLO2')
ON DUPLICATE KEY UPDATE username = username;

CREATE TABLE IF NOT EXISTS auth_tokens (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_user_fk INT UNSIGNED NOT NULL,
    token_hash VARCHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_auth_tokens_admin_user
        FOREIGN KEY (admin_user_fk) REFERENCES admin_users(id)
        ON DELETE CASCADE,
    UNIQUE KEY uq_auth_tokens_token_hash (token_hash)
);

CREATE TABLE IF NOT EXISTS general_bodies (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    name_hindi VARCHAR(200),
    name_odia VARCHAR(200),
    position VARCHAR(200) NOT NULL,
    image_path VARCHAR(255),
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS job_applications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    application_number VARCHAR(20) NOT NULL UNIQUE,
    opportunities_fk INT UNSIGNED NOT NULL,
    -- Position applicant is applying for
    position_applied VARCHAR(150) NOT NULL,
    -- Step 2: General details
    applicant_name VARCHAR(150) NOT NULL,
    gender VARCHAR(20) NOT NULL,
    date_of_birth DATE NOT NULL,
    email VARCHAR(150),
    mobile_no VARCHAR(20),
    marital_status VARCHAR(50),
    father_name VARCHAR(150),
    mother_name VARCHAR(150),
    guardian_name VARCHAR(150),
    present_address TEXT NOT NULL,
    permanent_address TEXT NOT NULL,
    -- Step 3: Photograph and signature (uploaded via /api/upload, path stored here)
    photograph_path VARCHAR(255),
    signature_path VARCHAR(255),
    -- Qualifications
    secondary_qualification VARCHAR(200),
    secondary_university VARCHAR(200),
    secondary_specialisation VARCHAR(200),
    secondary_passing_year VARCHAR(20),
    secondary_percentage VARCHAR(20),
    secondary_passing_category VARCHAR(200),
    higher_secondary_qualification VARCHAR(200),
    higher_secondary_university VARCHAR(200),
    higher_secondary_specialisation VARCHAR(200),
    higher_secondary_passing_year VARCHAR(20),
    higher_secondary_percentage VARCHAR(20),
    higher_secondary_passing_category VARCHAR(200),
    graduation_qualification VARCHAR(200),
    graduation_university VARCHAR(200),
    graduation_specialisation VARCHAR(200),
    graduation_passing_year VARCHAR(20),
    graduation_percentage VARCHAR(20),
    graduation_passing_category VARCHAR(200),
    post_graduation_qualification VARCHAR(200),
    post_graduation_university VARCHAR(200),
    post_graduation_specialisation VARCHAR(200),
    post_graduation_passing_year VARCHAR(20),
    post_graduation_percentage VARCHAR(20),
    post_graduation_passing_category VARCHAR(200),
    other_qualification VARCHAR(200),
    other_university VARCHAR(200),
    other_specialisation VARCHAR(200),
    other_passing_year VARCHAR(20),
    other_percentage VARCHAR(20),
    other_passing_category VARCHAR(200),
    -- Employment details
    employer_organization VARCHAR(200),
    designation VARCHAR(150),
    employment_period VARCHAR(100),
    grade_salary VARCHAR(100),
    job_description TEXT,
    -- Skills: computer literacy
    computer_skill_name VARCHAR(150),
    computer_skill_tools_proficiency TEXT,
    -- Skills: language proficiency
    language_english TINYINT(1) DEFAULT 0,
    language_odia TINYINT(1) DEFAULT 0,
    language_hindi TINYINT(1) DEFAULT 0,
    -- References
    reference1_name VARCHAR(150),
    reference1_phone VARCHAR(20),
    reference1_email VARCHAR(150),
    reference2_name VARCHAR(150),
    reference2_phone VARCHAR(20),
    reference2_email VARCHAR(150),
    status ENUM('pending', 'reviewed', 'shortlisted', 'rejected') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_job_applications_opportunity
        FOREIGN KEY (opportunities_fk) REFERENCES opportunities(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS job_application_sequences (
    id TINYINT UNSIGNED PRIMARY KEY,
    next_number INT UNSIGNED NOT NULL DEFAULT 0
);

INSERT IGNORE INTO job_application_sequences (id, next_number) VALUES (1, 0);

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

CREATE TABLE IF NOT EXISTS job_aspirants (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    description TEXT,
    resume_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

