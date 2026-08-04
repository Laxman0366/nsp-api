<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class NspRepository
{
    public function __construct(private readonly PDO $db)
    {
    }

    public function all(string $table, string $orderBy = 'id DESC'): array
    {
        $sql = sprintf('SELECT * FROM %s ORDER BY %s', $table, $orderBy);
        return $this->db->query($sql)->fetchAll();
    }

    public function find(string $table, int $id): ?array
    {
        $stmt = $this->db->prepare(sprintf('SELECT * FROM %s WHERE id = :id LIMIT 1', $table));
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch();
        return $result !== false ? $result : null;
    }

    public function existsById(string $table, int $id): bool
    {
        $stmt = $this->db->prepare(sprintf('SELECT 1 FROM %s WHERE id = :id LIMIT 1', $table));
        $stmt->execute(['id' => $id]);

        return $stmt->fetchColumn() !== false;
    }

    public function allProgrammeOverview(string $orderBy = 'display_order ASC, id DESC'): array
    {
        $sql = sprintf(
            'SELECT po.*, pm.programme_name, p.project_name
             FROM programme_overview po
             LEFT JOIN programme_master pm ON pm.id = po.programme_master_fk
             LEFT JOIN projects p ON p.id = po.projects_fk
             ORDER BY %s',
            $orderBy
        );

        return $this->db->query($sql)->fetchAll();
    }

    public function findProgrammeOverview(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT po.*, pm.programme_name, p.project_name
             FROM programme_overview po
             LEFT JOIN programme_master pm ON pm.id = po.programme_master_fk
             LEFT JOIN projects p ON p.id = po.projects_fk
             WHERE po.id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch();
        return $result !== false ? $result : null;
    }

    public function allProjects(string $orderBy = 'display_order ASC, id DESC'): array
    {
        $sql = sprintf(
            'SELECT p.*, pm.programme_name
             FROM projects p
             LEFT JOIN programme_master pm ON pm.id = p.programme_master_fk
             ORDER BY %s',
            $orderBy
        );

        return $this->db->query($sql)->fetchAll();
    }

    public function findProject(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, pm.programme_name
             FROM projects p
             LEFT JOIN programme_master pm ON pm.id = p.programme_master_fk
             WHERE p.id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch();
        return $result !== false ? $result : null;
    }

    public function create(string $table, array $payload): int
    {
        $columns = array_keys($payload);
        $placeholders = array_map(static fn (string $column): string => ':' . $column, $columns);

        $stmt = $this->db->prepare(
            sprintf(
                'INSERT INTO %s (%s) VALUES (%s)',
                $table,
                implode(', ', $columns),
                implode(', ', $placeholders)
            )
        );

        $stmt->execute($payload);

        return (int) $this->db->lastInsertId();
    }

    public function update(string $table, int $id, array $payload): bool
    {
        if ($payload === []) {
            return false;
        }

        $setParts = [];
        foreach (array_keys($payload) as $column) {
            $setParts[] = sprintf('%s = :%s', $column, $column);
        }

        $stmt = $this->db->prepare(
            sprintf('UPDATE %s SET %s WHERE id = :id', $table, implode(', ', $setParts))
        );

        $payload['id'] = $id;
        $stmt->execute($payload);

        return $stmt->rowCount() > 0;
    }

    public function delete(string $table, int $id): bool
    {
        $stmt = $this->db->prepare(sprintf('DELETE FROM %s WHERE id = :id', $table));
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }
}
