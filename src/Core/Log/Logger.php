<?php

declare(strict_types=1);

namespace App\Core\Log;

final readonly class Logger
{
    private const LEVELS = [
        'debug' => 100,
        'info' => 200,
        'warning' => 300,
        'error' => 400,
    ];

    /**
     * @param string $filePath
     * @param string $minLevel
     */
    public function __construct(
        private string $filePath,
        private string $minLevel = 'error'
    ) {}

    /**
     * @param string $message
     * @param array $context
     */
    public function debug(string $message, array $context = []): void
    {
        $this->write('debug', $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function info(string $message, array $context = []): void
    {
        $this->write('info', $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function warning(string $message, array $context = []): void
    {
        $this->write('warning', $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function error(string $message, array $context = []): void
    {
        $this->write('error', $message, $context);
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     */
    private function write(string $level, string $message, array $context = []): void
    {
        if (!$this->shouldLog($level)) {
            return;
        }

        $directory = dirname($this->filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $line = sprintf(
            "[%s] %s: %s %s\n",
            date('Y-m-d H:i:s'),
            strtoupper($level),
            $message,
            $context === [] ? '' : json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );

        file_put_contents($this->filePath, $line, FILE_APPEND);
    }

    /**
     * @param string $level
     * @return bool
     */
    private function shouldLog(string $level): bool
    {
        $currentLevel = self::LEVELS[$level] ?? self::LEVELS['error'];
        $minLevel = self::LEVELS[$this->minLevel] ?? self::LEVELS['error'];

        return $currentLevel >= $minLevel;
    }
}
