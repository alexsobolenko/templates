<?php

declare(strict_types=1);

namespace Tests\Fixtures\Core\DI;

final class ContainerServiceWithUnionType
{
    /**
     * @param string|int $id
     */
    public function __construct(
        public string|int $id
    ) {}
}
