<?php

declare(strict_types=1);

use Tests\Fixtures\Core\DI\ContainerServiceWithConfiguredArgument;

return [
    ContainerServiceWithConfiguredArgument::class => [
        'arguments' => [
            'name' => 'configured-name',
        ],
    ],
];
