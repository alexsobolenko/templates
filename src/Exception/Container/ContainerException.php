<?php

declare(strict_types=1);

namespace App\Exception\Container;

use App\Core\Http\Response;
use Psr\Container\ContainerExceptionInterface;

final class ContainerException extends \RuntimeException implements ContainerExceptionInterface
{
    /**
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $message = 'Container error.',
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
