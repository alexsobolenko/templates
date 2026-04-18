<?php

declare(strict_types=1);

namespace App\Core\Http;

final readonly class ParameterBag
{
    /**
     * @param array $data
     */
    public function __construct(
        private array $data = []
    ) {}

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * @param string $key
     * @param int $default
     * @return int
     */
    public function getInt(string $key, int $default = 0): int
    {
        return (int) $this->get($key, $default);
    }

    /**
     * @param string $key
     * @param bool $default
     * @return bool
     */
    public function getBool(string $key, bool $default = false): bool
    {
        return filter_var($this->get($key, $default), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->data;
    }
}
