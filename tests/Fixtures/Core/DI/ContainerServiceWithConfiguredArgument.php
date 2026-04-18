<?php

declare(strict_types=1);

namespace Tests\Fixtures\Core\DI;

final readonly class ContainerServiceWithConfiguredArgument
{
    /**
     * @param string $name
     */
    public function __construct(
        public string $name
    ) {}
}
