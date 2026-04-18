<?php

declare(strict_types=1);

namespace Tests\Unit\Core;

use App\Core\Config;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('unit.config')]
final class ConfigTest extends TestCase
{
    #[Group('unit.config.load')]
    public function testLoadsArrayConfigsFromPhpFiles(): void
    {
        $config = new Config(__DIR__ . '/../../Fixtures/Core/Config/files');

        self::assertSame('todo-list', $config->get('app', 'name'));
        self::assertSame(['path' => 'var/log/app.log'], $config->get('service', 'logger'));
    }

    #[Group('unit.config.load')]
    public function testIgnoresConfigFilesThatDoNotReturnArray(): void
    {
        $config = new Config(__DIR__ . '/../../Fixtures/Core/Config/files');

        self::assertSame('fallback', $config->get('invalid', 'key', 'fallback'));
    }

    #[Group('unit.config.get')]
    public function testReturnsDefaultValueWhenDirectoryDoesNotExistOrKeyIsMissing(): void
    {
        $config = new Config(__DIR__ . '/../../Fixtures/Core/Config/missing');

        self::assertSame('fallback', $config->get('app', 'name', 'fallback'));

        $loadedConfig = new Config(__DIR__ . '/../../Fixtures/Core/Config/files');

        self::assertSame('default-value', $loadedConfig->get('app', 'missing', 'default-value'));
    }
}
