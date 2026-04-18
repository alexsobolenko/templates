<?php

declare(strict_types=1);

namespace Tests\Unit\Core\Log;

use App\Core\Log\Logger;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('unit.log')]
final class LoggerTest extends TestCase
{
    /**
     * @var string
     */
    private string $directory;

    /**
     * @var string
     */
    private string $filePath;

    protected function setUp(): void
    {
        $this->directory = sys_get_temp_dir() . '/logger-test-' . uniqid('', true);
        $this->filePath = $this->directory . '/app.log';
    }

    protected function tearDown(): void
    {
        if (is_file($this->filePath)) {
            unlink($this->filePath);
        }

        if (is_dir($this->directory)) {
            rmdir($this->directory);
        }
    }

    #[Group('unit.log.write')]
    public function testWritesFormattedMessageWithContextAndCreatesDirectory(): void
    {
        $logger = new Logger($this->filePath, 'info');

        $logger->info('Task created', [
            'task' => 'demo',
        ]);

        self::assertDirectoryExists($this->directory);
        self::assertFileExists($this->filePath);

        $content = file_get_contents($this->filePath);

        self::assertIsString($content);
        self::assertMatchesRegularExpression(
            '/^\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] INFO: Task created \{"task":"demo"\}\n$/',
            $content
        );
    }

    #[Group('unit.log.threshold')]
    public function testSkipsMessageBelowConfiguredMinimumLevel(): void
    {
        $logger = new Logger($this->filePath, 'warning');

        $logger->info('This should not be written');

        self::assertFileDoesNotExist($this->filePath);
        self::assertDirectoryDoesNotExist($this->directory);
    }

    #[Group('unit.log.error')]
    public function testLogsErrorWithDefaultMinimumLevel(): void
    {
        $logger = new Logger($this->filePath);

        $logger->error('Something failed');

        self::assertFileExists($this->filePath);

        $content = file_get_contents($this->filePath);

        self::assertIsString($content);
        self::assertStringContainsString('ERROR: Something failed', $content);
    }
}
