<?php

declare(strict_types=1);

namespace Tests\Fixtures\Core\DI;

final readonly class ContainerServiceWithDependency
{
    /**
     * @param ContainerDependency $dependency
     */
    public function __construct(
        public ContainerDependency $dependency
    ) {}
}
