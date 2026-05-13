<?php

declare(strict_types=1);

use App\Core\App;
use App\Core\Service\Mailer;

/*
 * Service definitions for the application container.
 *
 * Classes without constructor arguments do not need to be listed here:
 * the container can instantiate them automatically. Add a definition only
 * when a class name should be replaced or constructor values should be passed
 * from configuration.
 */

return [
    Mailer::class => [
        'arguments' => [
            'dsn' => (string) App::env('MAILER_DSN', ''),
            'fromAddress' => (string) App::env('MAIL_FROM_ADDRESS', ''),
            'fromName' => App::env('MAIL_FROM_NAME'),
        ],
    ],
];
