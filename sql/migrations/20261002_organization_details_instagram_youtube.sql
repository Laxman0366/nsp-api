ALTER TABLE organization_details
    ADD COLUMN instagram_url VARCHAR(255) NULL AFTER facebook_url,
    ADD COLUMN youtube_url VARCHAR(255) NULL AFTER instagram_url;