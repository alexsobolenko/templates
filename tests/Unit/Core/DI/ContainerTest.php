<?php

declare(strict_types=1);

namespace Tests\Unit\Core\DI;

use App\Core\App;
use App\Core\Config;
use App\Core\DI\Container;
use App\Exception\Container\ContainerException;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\Core\DI\ContainerDependency;
use Tests\Fixtures\Core\DI\ContainerPlainService;
use Tests\Fixtures\Core\DI\ContainerServiceWithConfiguredArgument;
use Tests\Fixtures\Core\DI\ContainerServiceWithDependency;
use Tests\Fixtures\Core\DI\ContainerServiceWithUnionType;
use Tests\Fixtures\Core\DI\ContainerServiceWithoutTypeHint;

#[Group('unit')]
#[Group('unit.container')]
final class ContainerTest extends TestCase
{
    protected function setUp(): void
    {
        App::$config = new Config(__DIR__ . '/../../../Fixtures/Core/DI/config');
    }

    #[Group('unit.container.resolve')]
    public function testResolvesClassWithoutDependencies(): void
    {
        $container = new Container();
        $service = $container->resolve(ContainerPlainService::class);

        self::assertInstanceOf(ContainerPlainService::class, $service);
    }

    #[Group('unit.container.resolve')]
    public function testResolvesNestedObjectDependency(): void
    {
        $container = new Container();
        $service = $container->resolve(ContainerServiceWithDependency::class);

        self::assertInstanceOf(ContainerServiceWithDependency::class, $service);
        self::assertInstanceOf(ContainerDependency::class, $service->dependency);
    }

    #[Group('unit.container.resolve')]
    public function testResolvesArgumentsFromServiceConfig(): void
    {
        $container = new Container();
        $service = $container->resolve(ContainerServiceWithConfiguredArgument::class);

        self::assertSame('configured-name', $service->name);
    }

    #[Group('unit.container.resolve')]
    public function testReturnsSameResolvedInstanceOnRepeatedCall(): void
    {
        $container = new Container();

        self::assertSame(
            $container->resolve(ContainerPlainService::class),
            $container->resolve(ContainerPlainService::class)
        );
    }

    #[Group('unit.container.resolve')]
    public function testThrowsWhenParameterHasNoTypeHint(): void
    {
        $container = new Container();

        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('missing a type hint');

        $container->resolve(ContainerServiceWithoutTypeHint::class);
    }

    #[Group('unit.container.resolve')]
    public function testThrowsWhenParameterUsesUnionType(): void
    {
        $container = new Container();

        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('because of union type');

        $container->resolve(ContainerServiceWithUnionType::class);
    }
}
