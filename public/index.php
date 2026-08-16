<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\NspController;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Response;
use App\Repositories\NspRepository;

require_once __DIR__ . '/../src/Core/Database.php';
require_once __DIR__ . '/../src/Core/Response.php';
require_once __DIR__ . '/../src/Core/Auth.php';
require_once __DIR__ . '/../src/Repositories/NspRepository.php';
require_once __DIR__ . '/../src/Controllers/NspController.php';
require_once __DIR__ . '/../src/Controllers/AuthController.php';

/** Parses text fields from a raw multipart body (PUT/PATCH — PHP skips $_POST for these). */
function parseMultipartTextFields(string $contentType, string $rawBody): array
{
    $fields = [];

    if (!preg_match('/boundary=([^\s;]+)/i', $contentType, $m)) {
        return $fields;
    }

    $boundary = trim($m[1], '"\' ');
    $parts    = preg_split('/\r?\n--' . preg_quote($boundary, '/') . '(?:--)?\r?\n?/', "\r\n" . $rawBody);
    array_shift($parts);

    foreach ($parts as $part) {
        $sep = strpos($part, "\r\n\r\n");
        if ($sep === false) {
            continue;
        }

        $headerBlock = substr($part, 0, $sep);
        $disposition = '';
        foreach (explode("\r\n", ltrim($headerBlock, "\r\n")) as $line) {
            if (stripos($line, 'content-disposition:') === 0) {
                $disposition = substr($line, strlen('content-disposition:'));
            }
        }

        // Skip file fields
        if (preg_match('/\bfilename="/', $disposition)) {
            continue;
        }

        if (!preg_match('/\bname="([^"]*)"/', $disposition, $nm)) {
            continue;
        }

        $fields[$nm[1]] = rtrim(substr($part, $sep + 4), "\r\n");
    }

    return $fields;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

$basePath = '/nsp-api/public';
if (str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath));
}

$uri = rtrim($uri ?: '/', '/');
$uri = $uri === '' ? '/' : $uri;

$controller = new NspController(new NspRepository(Database::connection()));
$auth = new Auth(Database::connection());
$authController = new AuthController($auth);
$rawContentType = (string) ($_SERVER['CONTENT_TYPE'] ?? '');
$contentType    = strtolower($rawContentType);
$payload = [];

if (($method === 'OPTIONS')) {
    Response::noContent();
    exit;
}

if (str_contains($contentType, 'application/json')) {
    $input = file_get_contents('php://input');
    $payload = $input !== false && $input !== '' ? json_decode($input, true) : [];

    if ($input !== false && $input !== '' && !is_array($payload)) {
        Response::json([
            'success' => false,
            'message' => 'Invalid JSON payload.',
        ], 400);
        exit;
    }
} elseif (str_contains($contentType, 'multipart/form-data') || str_contains($contentType, 'application/x-www-form-urlencoded')) {
    if ($method === 'POST') {
        $payload = $_POST;
    } else {
        $rawBody = (string) (file_get_contents('php://input') ?: '');
        if (str_contains($contentType, 'multipart/form-data')) {
            $payload = parseMultipartTextFields($rawContentType, $rawBody);
        } else {
            parse_str($rawBody, $payload);
        }
    }
}

if ($method === 'GET' && $uri === '/api/health') {
    Response::json([
        'success' => true,
        'message' => 'NSP API is running.',
        'timestamp' => date(DATE_ATOM),
    ]);
    exit;
}

if ($method === 'GET' && $uri === '/api/urls') {
    $controller->urls();
    exit;
}

if ($method === 'POST' && $uri === '/api/login') {
    $authController->login($payload);
    exit;
}

// Job application submissions and file uploads are public; every other write request needs a valid Bearer token.
$isPublicPost = $method === 'POST' && in_array($uri, ['/api/job_applications', '/api/job_application_resumes', '/api/upload'], true);
if (in_array($method, ['POST', 'PUT', 'DELETE'], true) && !$isPublicPost) {
    $authUser = $auth->validateToken(Auth::bearerTokenFromHeader());
    if ($authUser === null) {
        Response::json([
            'success' => false,
            'message' => 'Unauthorized. Please login first.',
        ], 401);
        exit;
    }
}

$isJobApplicationCollection = in_array($uri, ['/api/job_applications', '/api/nsp/job_applications'], true);
if ($method === 'GET' && $isJobApplicationCollection) {
    $authUser = $auth->validateToken(Auth::bearerTokenFromHeader());
    if ($authUser === null) {
        Response::json([
            'success' => false,
            'message' => 'Unauthorized. Please login first.',
        ], 401);
        exit;
    }
}

if ($method === 'POST' && $uri === '/api/logout') {
    $authController->logout(Auth::bearerTokenFromHeader());
    exit;
}

if ($method === 'POST' && $uri === '/api/upload') {
    $controller->upload($payload, $_FILES);
    exit;
}

if ($method === 'POST' && $uri === '/api/send-mail') {
    $controller->sendMail($payload);
    exit;
}

if ($method === 'GET' && $uri === '/api/open_jobs') {
    $controller->openJobs();
    exit;
}

if (preg_match('#^/api/(?:nsp/)?([a-z_]+)(?:/(\d+))?$#', $uri, $matches) === 1) {
    $resource = $matches[1];
    $id = isset($matches[2]) ? (int) $matches[2] : null;

    if (!$controller->hasResource($resource)) {
        Response::json([
            'success' => false,
            'message' => 'Route not found.',
        ], 404);
        exit;
    }

    if ($id === null) {
        if ($method === 'GET') {
            $opportunityId = null;
            if ($resource === 'job_applications' && array_key_exists('opportunities_fk', $_GET)) {
                $opportunityId = filter_var($_GET['opportunities_fk'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
                if ($opportunityId === false) {
                    Response::json([
                        'success' => false,
                        'message' => 'Query parameter "opportunities_fk" must be a positive integer.',
                    ], 422);
                    exit;
                }
            }

            $controller->index($resource, $opportunityId);
            exit;
        }

        if ($method === 'POST') {
            $controller->store($resource, $payload);
            exit;
        }
    }

    if ($id !== null) {
        if ($method === 'GET') {
            $controller->show($resource, $id);
            exit;
        }

        if ($method === 'PUT') {
            $controller->update($resource, $id, $payload);
            exit;
        }

        if ($method === 'DELETE') {
            $controller->destroy($resource, $id);
            exit;
        }
    }

    Response::json([
        'success' => false,
        'message' => 'Method not allowed for this route.',
    ], 405);
    exit;
}

Response::json([
    'success' => false,
    'message' => 'Route not found.',
], 404);
