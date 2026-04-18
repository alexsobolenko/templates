<?php

declare(strict_types=1);

namespace App\Core\Service;

use App\Core\App;
use App\Core\Http\Response;

final class ExceptionHandler
{
    /**
     * @param \Throwable $exception
     * @return Response
     */
    public function handle(\Throwable $exception): Response
    {
        $debug = App::isDebug();

        $status = $this->resolveStatusCode($exception);
        $message = ($status >= 500 && !$debug) ? 'Server error' : $exception->getMessage();

        App::$logger->error($exception->getMessage(), [
            'status' => $status,
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ]);

        $accept = null;
        foreach (App::$request->headers->all() as $headerName => $value) {
            if (strtolower((string) $headerName) === 'accept' && is_scalar($value)) {
                $accept = strtolower((string) $value);
                break;
            }
        }

        $debugDetails = [
            'exception' => $exception::class,
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];

        if (is_string($accept) && str_contains($accept, 'application/json')) {
            $payload = [
                'ok' => false,
                'error' => [
                    'status' => $status,
                    'message' => $message,
                ],
            ];
            if ($debug) {
                $payload['error']['debug'] = $debugDetails;
            }

            return Response::json($payload, $status);
        }

        $params = [
            'title' => $status >= 500 ? 'Server error' : $message,
            'message' => $message,
            'debugDetails' => $debug ? $debugDetails : [],
        ];

        return App::$container->resolve(ViewRenderer::class)->render('error/page', $params, $status);
    }

    /**
     * @param \Throwable $exception
     * @return int
     */
    private function resolveStatusCode(\Throwable $exception): int
    {
        $code = (int) $exception->getCode();

        return ($code >= 400 && $code <= 599)
            ? $code
            : Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}
