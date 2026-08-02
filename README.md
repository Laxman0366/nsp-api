# NSP PHP API (XAMPP)

A lightweight PHP API project for NSP use cases with MySQL connection configured for localhost (XAMPP/phpMyAdmin).

## Project Structure

- `public/` - public entry point and routing
- `src/` - application code (config, core, controller, repository)
- `sql/schema.sql` - database and table schema

## Database Configuration

Database config file:

- `src/Config/database.php`

Default settings:

- Host: `127.0.0.1`
- Port: `3306`
- Database: `nsp`
- Username: `root`
- Password: `` (empty)

These defaults match standard XAMPP local MySQL setup.

## Setup (XAMPP)

1. Start Apache and MySQL from XAMPP Control Panel.
2. Open phpMyAdmin: `http://localhost/phpmyadmin`
3. Import schema:
   - Open SQL tab
   - Paste content of `sql/schema.sql`
   - Execute
4. Place/open this project at: `C:/xampp/htdocs/nsp-api`
5. Open API health endpoint:
   - `http://localhost/nsp-api/public/api/health`

## API Endpoints

### Health

- `GET /api/health`
- `GET /api/urls` - list all available API URLs

Example:

`http://localhost/nsp-api/public/api/health`

API URL listing example:

`http://localhost/nsp-api/public/api/urls`

### NSP Applicants CRUD

- `GET /api/nsp/applicants` - list all applicants
- `GET /api/nsp/applicants/{id}` - get one applicant
- `POST /api/nsp/applicants` - create applicant
- `PUT /api/nsp/applicants/{id}` - update applicant
- `DELETE /api/nsp/applicants/{id}` - delete applicant

Base URL in browser/Postman:

`http://localhost/nsp-api/public`

## Request Body (POST/PUT)

```json
{
  "full_name": "Aman Kumar",
  "email": "aman@example.com",
  "institute_name": "ABC Institute",
  "course_name": "B.Tech CSE",
  "scholarship_status": "pending"
}
```

## File Upload For file_path Resources

Create APIs for these resources accept binary upload via multipart form data and automatically save the file under `public/assets/{resource}`:

- `annual_reports`
- `audit_reports`
- `beneficiary_report`
- `staffs`
- `food_menu`

For these create endpoints, send `Content-Type: multipart/form-data` with:

- `file_path` (file upload field, or `file` as alias)
- other required text fields (for example, `title`)

The API stores a relative path like `/assets/annual_reports/<generated-file>` in the database `file_path` column.

Allowed values for `scholarship_status`:

- `pending`
- `approved`
- `rejected`

## Notes

- API accepts JSON for POST and PUT by default.
- Create endpoints for resources with `file_path` should use multipart form data file upload.
- If invalid JSON is sent, API returns `400`.
- If validation fails, API returns `422`.
