<?php

declare(strict_types=1);

namespace App\Exception\Model;

use App\Core\Http\Response;

final class ModelConfigurationException extends \RuntimeException
{
    /**
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $message = 'Model configuration error.',
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
