<?php

declare(strict_types=1);

namespace App\Attribute;

use App\Core\Http\Request;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final readonly class Route
{
    /**
     * @param string $path
     * @param array $methods
     * @param string|null $name
     */
    public function __construct(
        public string $path,
        public array $methods = [Request::METHOD_GET],
        public ?string $name = null
    ) {}
}
