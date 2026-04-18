<?php

declare(strict_types=1);

namespace Tests\Fixtures\Core\DI;

final class ContainerServiceWithoutTypeHint
{
    /**
     * @param mixed $name
     */
    public function __construct(
        $name
    ) {}
}
