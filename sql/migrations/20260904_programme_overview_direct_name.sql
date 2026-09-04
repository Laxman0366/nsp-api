ALTER TABLE programme_overview
    ADD COLUMN programme_name VARCHAR(200) NULL AFTER id;

UPDATE programme_overview po
LEFT JOIN programme_master pm ON pm.id = po.programme_master_fk
SET po.programme_name = COALESCE(pm.programme_name, '');

ALTER TABLE programme_overview
    MODIFY COLUMN programme_name VARCHAR(200) NOT NULL,
    DROP FOREIGN KEY fk_programme_overview_programme_master,
    DROP FOREIGN KEY fk_programme_overview_projects,
    DROP COLUMN programme_master_fk,
    DROP COLUMN projects_fk;