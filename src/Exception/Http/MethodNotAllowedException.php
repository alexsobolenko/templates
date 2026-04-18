<?php

declare(strict_types=1);

namespace App\Exception\Http;

use App\Core\Http\Response;

final class MethodNotAllowedException extends \RuntimeException
{
    /**
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $message = 'Method not allowed.',
        int $code = Response::HTTP_METHOD_NOT_ALLOWED,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
