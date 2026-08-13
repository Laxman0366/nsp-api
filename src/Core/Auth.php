<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

final class Auth
{
    private const TOKEN_TTL_SECONDS = 8 * 60 * 60;

    public function __construct(private readonly PDO $db)
    {
    }

    /** @return array{token: string, expires_at: string, user: array{id: int, username: string}}|null */
    public function login(string $username, string $password): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM admin_users WHERE id = 1 AND username = :username AND is_active = 1 LIMIT 1'
        );
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user === false || !password_verify($password, $user['password_hash'])) {
            return null;
        }

        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', time() + self::TOKEN_TTL_SECONDS);

        $insert = $this->db->prepare(
            'INSERT INTO auth_tokens (admin_user_fk, token_hash, expires_at) VALUES (:admin_user_fk, :token_hash, :expires_at)'
        );
        $insert->execute([
            'admin_user_fk' => $user['id'],
            'token_hash' => hash('sha256', $token),
            'expires_at' => $expiresAt,
        ]);

        return [
            'token' => $token,
            'expires_at' => $expiresAt,
            'user' => [
                'id' => (int) $user['id'],
                'username' => $user['username'],
            ],
        ];
    }

    /** @return array{id: int, username: string}|null */
    public function validateToken(?string $token): ?array
    {
        if ($token === null || $token === '') {
            return null;
        }

        $stmt = $this->db->prepare(
            'SELECT au.id, au.username FROM auth_tokens t
             INNER JOIN admin_users au ON au.id = t.admin_user_fk
             WHERE au.id = 1 AND t.token_hash = :token_hash AND t.expires_at > NOW() AND au.is_active = 1
             LIMIT 1'
        );
        $stmt->execute(['token_hash' => hash('sha256', $token)]);
        $user = $stmt->fetch();

        if ($user === false) {
            return null;
        }

        return ['id' => (int) $user['id'], 'username' => $user['username']];
    }

    public function logout(string $token): bool
    {
        $stmt = $this->db->prepare('DELETE FROM auth_tokens WHERE token_hash = :token_hash');
        $stmt->execute(['token_hash' => hash('sha256', $token)]);

        return $stmt->rowCount() > 0;
    }

    public static function bearerTokenFromHeader(): ?string
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? ($_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '');

        if ($header === '' && function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            $header = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        }

        if (preg_match('/Bearer\s+(\S+)/i', (string) $header, $matches) === 1) {
            return $matches[1];
        }

        return null;
    }
}
