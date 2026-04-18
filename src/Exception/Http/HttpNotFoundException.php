<?php

declare(strict_types=1);

namespace App\Exception\Http;

use App\Core\Http\Response;

final class HttpNotFoundException extends \RuntimeException
{
    /**
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $message = 'Not found.',
        int $code = Response::HTTP_NOT_FOUND,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
