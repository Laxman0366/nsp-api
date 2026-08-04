<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Response;
use App\Repositories\NspRepository;

final class NspController
{
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
            'allowed' => ['title', 'description', 'opening_date', 'closing_date', 'posted_by', 'detail_file_path', 'display_order'],
            'defaults' => ['display_order' => 0],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'tender_notices' => [
            'table' => 'tender_notices',
            'label' => 'Tender notice',
            'required' => ['title', 'opening_date'],
            'allowed' => ['title', 'description', 'opening_date', 'closing_date', 'posted_by', 'detail_file_path', 'display_order'],
            'defaults' => ['display_order' => 0],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'news_events' => [
            'table' => 'news_events',
            'label' => 'News event',
            'required' => ['title'],
            'allowed' => ['title', 'description', 'posted_by', 'detail_file_path', 'display_order'],
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
            'allowed' => ['title', 'beneficiary_name', 'details', 'image_path', 'display_order', 'is_active'],
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
            'allowed' => ['programme_name', 'display_order', 'is_active'],
            'defaults' => ['display_order' => 0, 'is_active' => 1],
            'order_by' => 'display_order ASC, id DESC',
        ],
        'projects' => [
            'table' => 'projects',
            'label' => 'Project',
            'required' => ['programme_master_fk', 'project_name'],
            'allowed' => ['programme_master_fk', 'project_name', 'project_details', 'achievement_details', 'image_path', 'other_image_paths', 'display_order', 'is_active'],
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
                'method' => 'POST',
                'path' => '/api/upload',
                'description' => 'Upload a file and receive its stored path',
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

        Response::json([
            'success' => true,
            'data' => $record,
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

        $resource = (string) ($payload['resource'] ?? 'uploads');
        if (!preg_match('/^[a-z_][a-z0-9_]*$/', $resource)) {
            Response::json(['success' => false, 'message' => 'Invalid resource name.'], 422);
            return;
        }

        $originalName = (string) ($uploaded['name'] ?? 'upload.bin');
        $extension = strtolower((string) pathinfo($originalName, PATHINFO_EXTENSION));
        if ($extension === '' || preg_match('/^[a-z0-9]+$/', $extension) !== 1) {
            $extension = 'bin';
        }

        $targetDirectory = __DIR__ . '/../../public/assets/' . $resource;
        if (!is_dir($targetDirectory) && !mkdir($targetDirectory, 0775, true) && !is_dir($targetDirectory)) {
            Response::json(['success' => false, 'message' => 'Unable to create asset directory.'], 500);
            return;
        }

        $fileName   = uniqid($resource . '_', true) . '.' . $extension;
        $targetPath = $targetDirectory . '/' . $fileName;

        if (!move_uploaded_file($tmpPath, $targetPath)) {
            Response::json(['success' => false, 'message' => 'Unable to save uploaded file.'], 500);
            return;
        }

        Response::json([
            'success' => true,
            'path'    => '/assets/' . $resource . '/' . $fileName,
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

        $id = $this->repository->create($config['table'], $data);
        $created = $this->repository->find($config['table'], $id);

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

        Response::json([
            'success' => true,
            'message' => $updated
                ? $config['label'] . ' updated successfully.'
                : $config['label'] . ' is already up to date.',
            'updated' => $updated,
            'data' => $this->repository->find($config['table'], $id),
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

        return $normalized;
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
