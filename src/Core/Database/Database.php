<?php

declare(strict_types=1);

namespace App\Core\Database;

final class Database
{
    /**
     * @var \PDO
     */
    private \PDO $pdo;

    /**
     * @param string $host
     * @param int $port
     * @param string $database
     * @param string $username
     * @param string $password
     */
    public function __construct(string $host, int $port, string $database, string $username, string $password)
    {
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $host, $port, $database);
        $this->pdo = new \PDO($dsn, $username, $password, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    /**
     * @param string $sql
     * @param array $params
     * @return \PDOStatement
     */
    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array|null
     */
    public function one(string $sql, array $params = []): ?array
    {
        $row = $this->query($sql, $params)->fetch();

        return $row === false ? null : $row;
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function all(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * @param string $sql
     * @param array $params
     * @return int
     */
    public function insert(string $sql, array $params = []): int
    {
        $this->query($sql, $params);

        return (int) $this->pdo->lastInsertId();
    }
}
