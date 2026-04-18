<?php

declare(strict_types=1);

namespace App\Core;

final class Config
{
    /**
     * @var array
     */
    private array $items = [];

    /**
     * @param string $directory
     */
    public function __construct(string $directory)
    {
        if (!is_dir($directory)) {
            return;
        }

        foreach (glob($directory . '/*.php') ?: [] as $file) {
            $type = pathinfo($file, PATHINFO_FILENAME);
            $config = include $file;
            if (!is_array($config)) {
                continue;
            }

            $this->items[$type] = $config;
        }
    }

    /**
     * @param string $type
     * @param string $id
     * @param mixed $default
     * @return mixed
     */
    public function get(string $type, string $id, mixed $default = null): mixed
    {
        if (array_key_exists($id, $this->items[$type] ?? [])) {
            return $this->items[$type][$id];
        }

        return $default;
    }
}
