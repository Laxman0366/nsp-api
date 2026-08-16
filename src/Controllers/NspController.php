<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Response;
use App\Repositories\NspRepository;

final class NspController
{
    private const CONTACT_EMAIL_TO = 'paikaraylaxman@gmail.com';

    /**
     * @var array<string, array{table: string, label: string, required: array<int, string>, allowed: array<int, string>, defaults: array<string, mixed>, order_by: string}>
     */
    private array $resources = [
        'applicants' => [
            'table' => 'nsp_applicants',
            'label' => 'Applicant',
            'required' => ['full_name', 'email', 'institute_name', 'course_name'],
            'allowed' => ['full_name', 'email', 'institute_name', 'course_name', 'scholarship_status'],
            'defaults' => ['scholarship_status' => 'pending'],
            'order_by' => 'id DESC',
        ],
        'banners' => [
            'table' => 'banners',
            'label' => 'Banner',
            'required' => ['image_path'],
            'allowed' => ['image_path', 'title', 'sub_title', 'alt_text', 'is_active', 'display_order'],
            'defaults' => ['is_active' => 1, 'display_order' => 0],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'contact_details' => [
            'table' => 'contact_details',
            'label' => 'Contact detail',
            'required' => ['mobile', 'email', 'address'],
            'allowed' => ['mobile', 'email', 'address', 'youtube_url', 'facebook_url', 'twitter_url'],
            'defaults' => [],
            'order_by' => 'id DESC',
        ],
        'bank_details' => [
            'table' => 'bank_details',
            'label' => 'Bank detail',
            'required' => ['account_name', 'account_number', 'ifsc_code', 'bank_name', 'branch_name'],
            'allowed' => ['account_name', 'account_number', 'ifsc_code', 'bank_name', 'branch_name', 'bank_image_path'],
            'defaults' => [],
            'order_by' => 'id DESC',
        ],
        'advertisements' => [
            'table' => 'advertisements',
            'label' => 'Advertisement',
            'required' => ['title', 'opening_date'],
            'allowed' => ['title', 'title_hindi', 'title_odia', 'description', 'description_hindi', 'description_odia', 'opening_date', 'closing_date', 'posted_by', 'detail_file_path', 'display_order'],
            'defaults' => ['display_order' => 0],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'tender_notices' => [
            'table' => 'tender_notices',
            'label' => 'Tender notice',
            'required' => ['title', 'opening_date'],
            'allowed' => ['title', 'title_hindi', 'title_odia', 'description', 'description_hindi', 'description_odia', 'opening_date', 'closing_date', 'posted_by', 'detail_file_path', 'display_order'],
            'defaults' => ['display_order' => 0, 'posted_by' => 'Nilachal Seva Pratisthan'],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'news_events' => [
            'table' => 'news_events',
            'label' => 'News event',
            'required' => ['title'],
            'allowed' => ['title', 'title_hindi', 'title_odia', 'description', 'description_hindi', 'description_odia', 'posted_by', 'detail_file_path', 'display_order'],
            'defaults' => ['display_order' => 0],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'partners' => [
            'table' => 'partners',
            'label' => 'Partner',
            'required' => ['title', 'logo_path'],
            'allowed' => ['title', 'logo_path', 'display_order'],
            'defaults' => ['display_order' => 0],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'milestones' => [
            'table' => 'milestones',
            'label' => 'Milestone',
            'required' => ['name'],
            'allowed' => ['name', 'count', 'display_order'],
            'defaults' => ['count' => 0, 'display_order' => 0],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'success_stories' => [
            'table' => 'success_stories',
            'label' => 'Success story',
            'required' => ['title', 'beneficiary_name'],
            'allowed' => ['title', 'title_hindi', 'title_odia', 'beneficiary_name', 'beneficiary_name_hindi', 'beneficiary_name_odia', 'details', 'details_hindi', 'details_odia', 'description', 'description_hindi', 'description_odia', 'image_path', 'display_order', 'is_active'],
            'defaults' => ['display_order' => 0, 'is_active' => 1],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'media_coverages' => [
            'table' => 'media_coverages',
            'label' => 'Media coverage',
            'required' => ['title'],
            'allowed' => ['title', 'date_time', 'image_path', 'display_order', 'is_active'],
            'defaults' => ['display_order' => 0, 'is_active' => 1],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'awards_recognitions' => [
            'table' => 'awards_recognitions',
            'label' => 'Award recognition',
            'required' => ['title'],
            'allowed' => ['title', 'date_time', 'image_path', 'display_order', 'is_active'],
            'defaults' => ['display_order' => 0, 'is_active' => 1],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'annual_reports' => [
            'table' => 'annual_reports',
            'label' => 'Annual report',
            'required' => ['title', 'file_path'],
            'allowed' => ['title', 'file_path', 'display_order', 'is_active'],
            'defaults' => ['display_order' => 0, 'is_active' => 1],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'legal_documents' => [
            'table' => 'legal_documents',
            'label' => 'Legal document',
            'required' => ['document_name', 'file_path'],
            'allowed' => ['document_name', 'file_path', 'display_order', 'is_active'],
            'defaults' => ['display_order' => 0, 'is_active' => 1],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'legal_status' => [
            'table' => 'legal_status',
            'label' => 'Legal status',
            'required' => ['status_details'],
            'allowed' => ['status_details', 'display_order', 'is_active'],
            'defaults' => ['display_order' => 0, 'is_active' => 1],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'audit_reports' => [
            'table' => 'audit_reports',
            'label' => 'Audit report',
            'required' => ['title', 'file_path'],
            'allowed' => ['title', 'file_path', 'display_order', 'is_active'],
            'defaults' => ['display_order' => 0, 'is_active' => 1],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'beneficiary_report' => [
            'table' => 'beneficiary_report',
            'label' => 'Beneficiary report',
            'required' => ['project_name', 'no_of_beneficiaries', 'file_path'],
            'allowed' => ['project_name', 'no_of_beneficiaries', 'file_last_update_datetime', 'file_path', 'display_order', 'is_active'],
            'defaults' => ['no_of_beneficiaries' => 0, 'display_order' => 0, 'is_active' => 1],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'staffs' => [
            'table' => 'staffs',
            'label' => 'Staff',
            'required' => ['title', 'file_path'],
            'allowed' => ['title', 'file_path', 'display_order', 'is_active'],
            'defaults' => ['display_order' => 0, 'is_active' => 1],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'food_menu' => [
            'table' => 'food_menu',
            'label' => 'Food menu',
            'required' => ['title', 'file_path'],
            'allowed' => ['title', 'file_path', 'display_order', 'is_active'],
            'defaults' => ['display_order' => 0, 'is_active' => 1],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'image_gallery' => [
            'table' => 'image_gallery',
            'label' => 'Image gallery item',
            'required' => ['title', 'image_path'],
            'allowed' => ['title', 'image_path', 'display_order', 'is_active'],
            'defaults' => ['display_order' => 0, 'is_active' => 1],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'video_gallery' => [
            'table' => 'video_gallery',
            'label' => 'Video gallery item',
            'required' => ['title', 'video_path'],
            'allowed' => ['title', 'video_path', 'display_order', 'is_active'],
            'defaults' => ['display_order' => 0, 'is_active' => 1],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'programme_master' => [
            'table' => 'programme_master',
            'label' => 'Programme',
            'required' => ['programme_name'],
            'allowed' => ['programme_name', 'programme_name_hindi', 'programme_name_odia', 'display_order', 'is_active'],
            'defaults' => ['display_order' => 0, 'is_active' => 1],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'projects' => [
            'table' => 'projects',
            'label' => 'Project',
            'required' => ['programme_master_fk', 'project_name'],
            'allowed' => ['programme_master_fk', 'project_name', 'project_name_hindi', 'project_name_odia', 'project_details', 'project_details_hindi', 'project_details_odia', 'achievement_details', 'achievement_details_hindi', 'achievement_details_odia', 'image_path', 'other_image_paths', 'display_order', 'is_active'],
            'defaults' => ['display_order' => 0, 'is_active' => 1],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'programme_overview' => [
            'table' => 'programme_overview',
            'label' => 'Programme overview',
            'required' => ['programme_master_fk', 'projects_fk'],
            'allowed' => ['programme_master_fk', 'projects_fk', 'starting_year', 'supported_by', 'status', 'strength', 'beneficiaries_covered', 'display_order', 'is_active'],
            'defaults' => ['beneficiaries_covered' => 0, 'display_order' => 0, 'is_active' => 1],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'opportunities' => [
            'table' => 'opportunities',
            'label' => 'Opportunity',
            'required' => ['name_of_post', 'req_qualification', 'number_of_post', 'remuneration'],
            'allowed' => ['name_of_post', 'req_qualification', 'number_of_post', 'remuneration', 'lower_age', 'upper_age', 'closing_date', 'display_order', 'is_active'],
            'defaults' => ['number_of_post' => 0, 'display_order' => 0, 'is_active' => 1],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'cctv_details' => [
            'table' => 'cctv_details',
            'label' => 'CCTV detail',
            'required' => ['project_name', 'serial_number'],
            'allowed' => ['project_name', 'project_name_hindi', 'project_name_odia', 'serial_number', 'display_order', 'is_active'],
            'defaults' => ['display_order' => 0, 'is_active' => 1],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'organization_details' => [
            'table' => 'organization_details',
            'label' => 'Organization detail',
            'required' => ['phone_number', 'email', 'office_address'],
            'allowed' => ['phone_number', 'email', 'office_address', 'office_address_hindi', 'office_address_odia', 'facebook_url', 'twitter_url', 'linkedin_url'],
            'defaults' => [],
            'order_by' => 'id DESC',
        ],
        'donations' => [
            'table' => 'donations',
            'label' => 'Donation',
            'required' => ['donor_name', 'donation_amount', 'donation_date'],
            'allowed' => ['donor_name', 'donation_amount', 'donation_date', 'display_order', 'is_active'],
            'defaults' => ['display_order' => 0, 'is_active' => 1],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'governing_bodies' => [
            'table' => 'governing_bodies',
            'label' => 'Governing body',
            'required' => ['name', 'position', 'qualification'],
            'allowed' => ['name', 'name_hindi', 'name_odia', 'position', 'qualification', 'image_path', 'message', 'message_hindi', 'message_odia', 'display_order', 'is_active'],
            'defaults' => ['display_order' => 0, 'is_active' => 1],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'general_bodies' => [
            'table' => 'general_bodies',
            'label' => 'General body',
            'required' => ['name', 'position'],
            'allowed' => ['name', 'name_hindi', 'name_odia', 'position', 'image_path', 'display_order', 'is_active'],
            'defaults' => ['display_order' => 0, 'is_active' => 1],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'job_applications' => [
            'table' => 'job_applications',
            'label' => 'Job application',
            'required' => ['position_applied', 'applicant_name', 'gender', 'date_of_birth', 'present_address', 'permanent_address'],
            'allowed' => [
                'position_applied', 'applicant_name', 'gender', 'date_of_birth', 'email', 'mobile_no', 'marital_status',
                'father_name', 'mother_name', 'guardian_name',
                'present_address', 'permanent_address', 'photograph_path', 'signature_path',
                'secondary_qualification', 'secondary_university', 'secondary_specialisation', 'secondary_passing_year',
                'secondary_percentage', 'secondary_passing_category',
                'higher_secondary_qualification', 'higher_secondary_university', 'higher_secondary_specialisation', 'higher_secondary_passing_year',
                'higher_secondary_percentage', 'higher_secondary_passing_category',
                'graduation_qualification', 'graduation_university', 'graduation_specialisation', 'graduation_passing_year',
                'graduation_percentage', 'graduation_passing_category',
                'post_graduation_qualification', 'post_graduation_university', 'post_graduation_specialisation', 'post_graduation_passing_year',
                'post_graduation_percentage', 'post_graduation_passing_category',
                'other_qualification', 'other_university', 'other_specialisation', 'other_passing_year', 'other_percentage', 'other_passing_category',
                'employer_organization', 'designation', 'employment_period', 'grade_salary', 'job_description',
                'computer_skill_name', 'computer_skill_tools_proficiency',
                'language_english', 'language_odia', 'language_hindi',
                'reference1_name', 'reference1_phone', 'reference1_email', 'reference2_name', 'reference2_phone', 'reference2_email',
                'status',
            ],
            'defaults' => ['language_english' => 0, 'language_odia' => 0, 'language_hindi' => 0, 'status' => 'pending'],
            'order_by' => 'id DESC',
        ],
        'job_application_resumes' => [
            'table' => 'job_application_resumes',
            'label' => 'Job application resume',
            'required' => ['job_applications_fk'],
            'allowed' => ['job_applications_fk', 'generated_resume_path'],
            'defaults' => [],
            'order_by' => 'id DESC',
        ],
    ];

    public function __construct(private readonly NspRepository $repository)
    {
    }

    public function hasResource(string $resource): bool
    {
        return isset($this->resources[$resource]);
    }

    public function urls(): void
    {
        $baseRoutes = [
            [
                'method' => 'GET',
                'path' => '/api/health',
                'description' => 'API health check',
            ],
            [
                'method' => 'GET',
                'path' => '/api/urls',
                'description' => 'List all available API URLs',
            ],
            [
                'method' => 'GET',
                'path' => '/api/open_jobs',
                'description' => 'List open opportunities with vacancy count and applied candidate count',
            ],
            [
                'method' => 'POST',
                'path' => '/api/login',
                'description' => 'Login with username/password, returns Bearer token (required for all write requests)',
            ],
            [
                'method' => 'POST',
                'path' => '/api/logout',
                'description' => 'Logout and invalidate the current Bearer token (requires Authorization header)',
            ],
            [
                'method' => 'POST',
                'path' => '/api/upload',
                'description' => 'Upload a file and receive its stored path',
            ],
            [
                'method' => 'POST',
                'path' => '/api/send-mail',
                'description' => 'Send submitted form data to configured mail inbox',
            ],
        ];

        $resourceRoutes = [];
        foreach (array_keys($this->resources) as $resource) {
            $resourceRoutes[] = [
                'method' => 'GET',
                'path' => '/api/' . $resource,
                'description' => 'List ' . $resource,
            ];
            $resourceRoutes[] = [
                'method' => 'GET',
                'path' => '/api/' . $resource . '/{id}',
                'description' => 'Get single ' . $resource . ' record',
            ];
            $resourceRoutes[] = [
                'method' => 'POST',
                'path' => '/api/' . $resource,
                'description' => 'Create ' . $resource . ' record',
            ];
            $resourceRoutes[] = [
                'method' => 'PUT',
                'path' => '/api/' . $resource . '/{id}',
                'description' => 'Update ' . $resource . ' record',
            ];
            $resourceRoutes[] = [
                'method' => 'DELETE',
                'path' => '/api/' . $resource . '/{id}',
                'description' => 'Delete ' . $resource . ' record',
            ];

            // Backward-compatible NSP-prefixed aliases.
            $resourceRoutes[] = [
                'method' => 'GET',
                'path' => '/api/nsp/' . $resource,
                'description' => 'List ' . $resource . ' (NSP alias)',
            ];
            $resourceRoutes[] = [
                'method' => 'GET',
                'path' => '/api/nsp/' . $resource . '/{id}',
                'description' => 'Get single ' . $resource . ' record (NSP alias)',
            ];
            $resourceRoutes[] = [
                'method' => 'POST',
                'path' => '/api/nsp/' . $resource,
                'description' => 'Create ' . $resource . ' record (NSP alias)',
            ];
            $resourceRoutes[] = [
                'method' => 'PUT',
                'path' => '/api/nsp/' . $resource . '/{id}',
                'description' => 'Update ' . $resource . ' record (NSP alias)',
            ];
            $resourceRoutes[] = [
                'method' => 'DELETE',
                'path' => '/api/nsp/' . $resource . '/{id}',
                'description' => 'Delete ' . $resource . ' record (NSP alias)',
            ];
        }

        Response::json([
            'success' => true,
            'count' => count($baseRoutes) + count($resourceRoutes),
            'routes' => array_merge($baseRoutes, $resourceRoutes),
        ]);
    }

    public function index(string $resource): void
    {
        $config = $this->resources[$resource];
        $records = match ($resource) {
            'programme_overview' => $this->repository->allProgrammeOverview($config['order_by']),
            'projects' => $this->repository->allProjects($config['order_by']),
            default => $this->repository->all($config['table'], $config['order_by']),
        };

        $records = array_map(fn (array $record): array => $this->transformRecordForResponse($resource, $record), $records);

        Response::json([
            'success' => true,
            'data' => $records,
        ]);
    }

    public function show(string $resource, int $id): void
    {
        $config = $this->resources[$resource];
        $record = match ($resource) {
            'programme_overview' => $this->repository->findProgrammeOverview($id),
            'projects' => $this->repository->findProject($id),
            default => $this->repository->find($config['table'], $id),
        };

        if ($record === null) {
            Response::json([
                'success' => false,
                'message' => $config['label'] . ' not found.',
            ], 404);
            return;
        }

        $record = $this->transformRecordForResponse($resource, $record);

        Response::json([
            'success' => true,
            'data' => $record,
        ]);
    }

    public function openJobs(): void
    {
        $records = array_map(static fn (array $row): array => [
            'position_for' => $row['position_for'],
            'closing_date' => $row['closing_date'],
            'number_of_vacancies' => (int) $row['number_of_vacancies'],
            'applied_candidates' => (int) $row['applied_candidates'],
        ], $this->repository->openJobs());

        Response::json([
            'success' => true,
            'count' => count($records),
            'data' => $records,
        ]);
    }

    public function upload(array $payload, array $files): void
    {
        $uploaded = $files['file'] ?? $files['file_path'] ?? null;
        if (!is_array($uploaded) || ($uploaded['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            Response::json(['success' => false, 'message' => 'No file provided. Send the file in the "file" field.'], 422);
            return;
        }

        $error = (int) ($uploaded['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($error !== UPLOAD_ERR_OK) {
            Response::json(['success' => false, 'message' => 'File upload failed. Please try again.'], 422);
            return;
        }

        $tmpPath = (string) ($uploaded['tmp_name'] ?? '');
        if ($tmpPath === '' || !is_uploaded_file($tmpPath)) {
            Response::json(['success' => false, 'message' => 'Invalid uploaded file.'], 422);
            return;
        }

        $folder = trim((string) ($payload['folder'] ?? ''));
        if ($folder === '' && array_key_exists('resource', $payload)) {
            $folder = trim((string) $payload['resource']);
        }

        if ($folder !== '' && preg_match('#^[a-zA-Z0-9_-]+(?:/[a-zA-Z0-9_-]+)*$#', $folder) !== 1) {
            Response::json(['success' => false, 'message' => 'Invalid folder name.'], 422);
            return;
        }

        $originalName = (string) ($uploaded['name'] ?? 'upload.bin');
        $extension = strtolower((string) pathinfo($originalName, PATHINFO_EXTENSION));
        if ($extension === '' || preg_match('/^[a-z0-9]+$/', $extension) !== 1) {
            $extension = 'bin';
        }

        $assetsDirectory = realpath(__DIR__ . '/../../public/assets');
        if ($assetsDirectory === false) {
            $assetsDirectory = __DIR__ . '/../../public/assets';
        }

        $targetDirectory = $assetsDirectory . ($folder !== '' ? DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $folder) : '');
        if (!is_dir($targetDirectory) && !mkdir($targetDirectory, 0775, true) && !is_dir($targetDirectory)) {
            Response::json(['success' => false, 'message' => 'Unable to create asset directory.'], 500);
            return;
        }

        $fileName   = uniqid('upload_', true) . '.' . $extension;
        $targetPath = $targetDirectory . '/' . $fileName;

        if (!move_uploaded_file($tmpPath, $targetPath)) {
            Response::json(['success' => false, 'message' => 'Unable to save uploaded file.'], 500);
            return;
        }

        Response::json([
            'success' => true,
            'path'    => '/assets' . ($folder !== '' ? '/' . str_replace(DIRECTORY_SEPARATOR, '/', $folder) : '') . '/' . $fileName,
        ], 201);
    }

    public function sendMail(array $payload): void
    {
        if ($payload === []) {
            Response::json([
                'success' => false,
                'message' => 'Request payload is required.',
            ], 422);
            return;
        }

        $name = trim((string) ($payload['name'] ?? $payload['full_name'] ?? ''));
        $email = trim((string) ($payload['email'] ?? ''));
        $subjectInput = trim((string) ($payload['subject'] ?? 'NSP Website Contact'));
        $messageInput = trim((string) ($payload['message'] ?? $payload['description'] ?? ''));

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Response::json([
                'success' => false,
                'message' => 'Field "email" must be a valid email address.',
            ], 422);
            return;
        }

        if ($messageInput === '' && count($payload) === 0) {
            Response::json([
                'success' => false,
                'message' => 'Please provide at least one field to send.',
            ], 422);
            return;
        }

        $safeSubject = preg_replace('/[\r\n]+/', ' ', $subjectInput) ?: 'NSP Website Contact';

        $lines = [
            'A new form submission was received.',
            '',
            'Name: ' . ($name !== '' ? $name : 'N/A'),
            'Email: ' . ($email !== '' ? $email : 'N/A'),
            'Message: ' . ($messageInput !== '' ? $messageInput : 'N/A'),
            '',
            'All fields received:',
        ];

        foreach ($payload as $key => $value) {
            $label = preg_replace('/[^a-z0-9_\- ]/i', '', (string) $key) ?: 'field';
            if (is_array($value)) {
                $encoded = json_encode($value);
                $value = $encoded === false ? '[array]' : $encoded;
            }

            $sanitizedValue = preg_replace('/[\r\n]+/', ' ', trim((string) $value));
            $lines[] = sprintf('- %s: %s', $label, $sanitizedValue !== '' ? $sanitizedValue : '');
        }

        $body = implode("\r\n", $lines);

        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/plain; charset=UTF-8',
            'From: no-reply@nsp.local',
            'Reply-To: ' . ($email !== '' ? $email : 'no-reply@nsp.local'),
            'X-Mailer: PHP/' . phpversion(),
        ];

        $sent = @mail(self::CONTACT_EMAIL_TO, $safeSubject, $body, implode("\r\n", $headers));

        if (!$sent) {
            Response::json([
                'success' => false,
                'message' => 'Unable to send email right now. Please check mail server configuration.',
            ], 500);
            return;
        }

        Response::json([
            'success' => true,
            'message' => 'Mail sent successfully.',
            'sent_to' => self::CONTACT_EMAIL_TO,
        ], 201);
    }

    public function store(string $resource, array $payload): void
    {
        $config = $this->resources[$resource];
        $data = $this->preparePayload($resource, $payload, false);

        $validation = $this->validate($resource, $data, false);
        if ($validation !== null) {
            Response::json($validation, 422);
            return;
        }

        $id = $resource === 'job_applications'
            ? $this->repository->createJobApplication($data)
            : $this->repository->create($config['table'], $data);
        $created = $this->repository->find($config['table'], $id);
        if ($created !== null) {
            $created = $this->transformRecordForResponse($resource, $created);
        }

        Response::json([
            'success' => true,
            'message' => $config['label'] . ' created successfully.',
            'data' => $created,
        ], 201);
    }

    public function update(string $resource, int $id, array $payload): void
    {
        $config = $this->resources[$resource];

        if ($this->repository->find($config['table'], $id) === null) {
            Response::json([
                'success' => false,
                'message' => $config['label'] . ' not found.',
            ], 404);
            return;
        }

        $data = $this->preparePayload($resource, $payload, true);
        $validation = $this->validate($resource, $data, true);
        if ($validation !== null) {
            Response::json($validation, 422);
            return;
        }

        $updated = $this->repository->update($config['table'], $id, $data);

        $record = $this->repository->find($config['table'], $id);
        if ($record !== null) {
            $record = $this->transformRecordForResponse($resource, $record);
        }

        Response::json([
            'success' => true,
            'message' => $updated
                ? $config['label'] . ' updated successfully.'
                : $config['label'] . ' is already up to date.',
            'updated' => $updated,
            'data' => $record,
        ]);
    }

    public function destroy(string $resource, int $id): void
    {
        $config = $this->resources[$resource];
        $record = $this->repository->find($config['table'], $id);

        if ($record === null) {
            Response::json([
                'success' => false,
                'message' => $config['label'] . ' not found.',
            ], 404);
            return;
        }

        $this->repository->delete($config['table'], $id);
        $this->deleteLinkedFile($record['file_path'] ?? null);

        Response::json([
            'success' => true,
            'message' => $config['label'] . ' deleted successfully.',
        ]);
    }

    private function deleteLinkedFile(mixed $filePath): void
    {
        if (!is_string($filePath) || $filePath === '') {
            return;
        }

        $assetsBase = realpath(__DIR__ . '/../../public/assets');
        $fullPath   = realpath(__DIR__ . '/../../public' . $filePath);

        // Ensure the resolved path is inside the assets directory before deleting
        if ($assetsBase !== false && $fullPath !== false && str_starts_with($fullPath, $assetsBase . DIRECTORY_SEPARATOR)) {
            @unlink($fullPath);
        }
    }

    private function preparePayload(string $resource, array $payload, bool $isUpdate): array
    {
        $config = $this->resources[$resource];
        $normalized = [];

        foreach ($payload as $key => $value) {
            $normalizedKey = match ($key) {
                'isActive' => 'is_active',
                default => $key,
            };

            if ($normalizedKey === 'file_path' && (is_null($value) || (is_string($value) && trim($value) === ''))) {
                continue;
            }

            if (in_array($normalizedKey, $config['allowed'], true)) {
                $normalized[$normalizedKey] = $value;
            }
        }

        if (!$isUpdate) {
            foreach ($config['defaults'] as $key => $value) {
                if (!array_key_exists($key, $normalized)) {
                    $normalized[$key] = $value;
                }
            }
        }

        if ($resource === 'success_stories') {
            if (!array_key_exists('details', $normalized) && array_key_exists('description', $normalized)) {
                $normalized['details'] = $normalized['description'];
            }

            if (!array_key_exists('details_hindi', $normalized) && array_key_exists('description_hindi', $normalized)) {
                $normalized['details_hindi'] = $normalized['description_hindi'];
            }

            if (!array_key_exists('details_odia', $normalized) && array_key_exists('description_odia', $normalized)) {
                $normalized['details_odia'] = $normalized['description_odia'];
            }

            unset($normalized['description'], $normalized['description_hindi'], $normalized['description_odia']);
        }

        return $normalized;
    }

    private function transformRecordForResponse(string $resource, array $record): array
    {
        if ($resource === 'success_stories') {
            $record['description'] = $record['details'] ?? null;
            $record['description_hindi'] = $record['details_hindi'] ?? null;
            $record['description_odia'] = $record['details_odia'] ?? null;
        }

        return $record;
    }

    private function handleFileUploadForCreate(string $resource, array &$payload, array $files): ?string
    {
        $config = $this->resources[$resource];
        if (!in_array('file_path', $config['allowed'], true)) {
            return null;
        }

        $uploaded = $files['file_path'] ?? $files['file'] ?? null;
        if (!is_array($uploaded) || ($uploaded['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return 'Field "file_path" must be uploaded as a file.';
        }

        return $this->storeUploadedFile($resource, $payload, $uploaded);
    }

    private function handleFileUploadForUpdate(string $resource, array &$payload, array $files): ?string
    {
        $config = $this->resources[$resource];
        if (!in_array('file_path', $config['allowed'], true)) {
            return null;
        }

        $uploaded = $files['file_path'] ?? $files['file'] ?? null;
        if (!is_array($uploaded)) {
            if (isset($payload['file_path']) && (is_string($payload['file_path']) && trim($payload['file_path']) === '')) {
                unset($payload['file_path']);
            }

            return null;
        }

        $error = (int) ($uploaded['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($error === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($error !== UPLOAD_ERR_OK) {
            return 'File upload failed. Please try again.';
        }

        return $this->storeUploadedFile($resource, $payload, $uploaded);
    }

    private function storeUploadedFile(string $resource, array &$payload, array $uploaded): ?string
    {
        $isRaw   = (bool) ($uploaded['_raw'] ?? false);
        $tmpPath = (string) ($uploaded['tmp_name'] ?? '');

        $invalid = $tmpPath === ''
            || (!$isRaw && !is_uploaded_file($tmpPath))
            || ($isRaw && !is_readable($tmpPath));

        if ($invalid) {
            return 'Invalid uploaded file.';
        }

        $originalName = (string) ($uploaded['name'] ?? 'upload.bin');
        $extension = strtolower((string) pathinfo($originalName, PATHINFO_EXTENSION));
        if ($extension === '' || preg_match('/^[a-z0-9]+$/', $extension) !== 1) {
            $extension = 'bin';
        }

        $targetDirectory = __DIR__ . '/../../public/assets/' . $resource;
        if (!is_dir($targetDirectory) && !mkdir($targetDirectory, 0775, true) && !is_dir($targetDirectory)) {
            return 'Unable to create asset directory.';
        }

        $fileName   = uniqid($resource . '_', true) . '.' . $extension;
        $targetPath = $targetDirectory . '/' . $fileName;

        $moved = $isRaw ? rename($tmpPath, $targetPath) : move_uploaded_file($tmpPath, $targetPath);
        if (!$moved) {
            return 'Unable to save uploaded file.';
        }

        $payload['file_path'] = '/assets/' . $resource . '/' . $fileName;

        return null;
    }

    private function validate(string $resource, array $payload, bool $isUpdate): ?array
    {
        $config = $this->resources[$resource];

        if ($isUpdate && $payload === []) {
            return [
                'success' => false,
                'message' => 'No valid fields provided for update.',
            ];
        }

        if (!$isUpdate) {
            foreach ($config['required'] as $field) {
                if (!array_key_exists($field, $payload) || trim((string) $payload[$field]) === '') {
                    return [
                        'success' => false,
                        'message' => sprintf('Field "%s" is required.', $field),
                    ];
                }
            }
        }

        if (isset($payload['email']) && !filter_var((string) $payload['email'], FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Field "email" must be a valid email address.',
            ];
        }

        if ($resource === 'applicants' && isset($payload['scholarship_status'])) {
            $allowedStatus = ['pending', 'approved', 'rejected'];
            if (!in_array($payload['scholarship_status'], $allowedStatus, true)) {
                return [
                    'success' => false,
                    'message' => 'scholarship_status must be one of: pending, approved, rejected.',
                ];
            }
        }

        if ($resource === 'projects' && array_key_exists('programme_master_fk', $payload)) {
            $programmeMasterFk = filter_var($payload['programme_master_fk'], FILTER_VALIDATE_INT);
            if ($programmeMasterFk === false || $programmeMasterFk <= 0) {
                return [
                    'success' => false,
                    'message' => 'programme_master_fk must be a positive integer.',
                ];
            }

            if (!$this->repository->existsById('programme_master', $programmeMasterFk)) {
                return [
                    'success' => false,
                    'message' => 'programme_master_fk does not reference an existing programme.',
                ];
            }
        }

        if ($resource === 'programme_overview') {
            if (array_key_exists('programme_master_fk', $payload)) {
                $programmeMasterFk = filter_var($payload['programme_master_fk'], FILTER_VALIDATE_INT);
                if ($programmeMasterFk === false || $programmeMasterFk <= 0) {
                    return [
                        'success' => false,
                        'message' => 'programme_master_fk must be a positive integer.',
                    ];
                }

                if (!$this->repository->existsById('programme_master', $programmeMasterFk)) {
                    return [
                        'success' => false,
                        'message' => 'programme_master_fk does not reference an existing programme.',
                    ];
                }
            }

            if (array_key_exists('projects_fk', $payload)) {
                $projectFk = filter_var($payload['projects_fk'], FILTER_VALIDATE_INT);
                if ($projectFk === false || $projectFk <= 0) {
                    return [
                        'success' => false,
                        'message' => 'projects_fk must be a positive integer.',
                    ];
                }

                if (!$this->repository->existsById('projects', $projectFk)) {
                    return [
                        'success' => false,
                        'message' => 'projects_fk does not reference an existing project.',
                    ];
                }
            }
        }

        return null;
    }
}
