<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Response;

final class AuthController
{
    public function __construct(private readonly Auth $auth)
    {
    }

    public function login(array $payload): void
    {
        $username = trim((string) ($payload['username'] ?? ''));
        $password = (string) ($payload['password'] ?? '');

        if ($username === '' || $password === '') {
            Response::json([
                'success' => false,
                'message' => 'Username and password are required.',
            ], 422);
            return;
        }

        $result = $this->auth->login($username, $password);
        if ($result === null) {
            Response::json([
                'success' => false,
                'message' => 'Invalid username or password.',
            ], 401);
            return;
        }

        Response::json([
            'success' => true,
            'message' => 'Login successful.',
            'token' => $result['token'],
            'token_type' => 'Bearer',
            'expires_at' => $result['expires_at'],
            'user' => $result['user'],
        ]);
    }

    public function logout(string $token): void
    {
        $this->auth->logout($token);

        Response::json([
            'success' => true,
            'message' => 'Logout successful.',
        ]);
    }
}
