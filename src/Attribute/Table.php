<?php

declare(strict_types=1);

namespace App\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
final readonly class Table
{
    /**
     * @param string $name
     */
    public function __construct(
        public string $name
    ) {}
}
