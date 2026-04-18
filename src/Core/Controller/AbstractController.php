<?php

declare(strict_types=1);

namespace App\Core\Controller;

use App\Core\App;
use App\Core\Http\Response;
use App\Core\Service\ViewRenderer;

abstract class AbstractController
{
    /**
     * @param string $view
     * @param array $params
     * @param int $status
     * @return Response
     */
    protected function render(string $view, array $params = [], int $status = Response::HTTP_OK): Response
    {
        return App::$container->resolve(ViewRenderer::class)->render($view, $params, $status);
    }

    /**
     * @param array $data
     * @param int $status
     * @return Response
     */
    protected function json(array $data, int $status = Response::HTTP_OK): Response
    {
        return Response::json($data, $status);
    }

    /**
     * @param string $url
     * @param int $status
     * @return Response
     */
    protected function redirect(string $url, int $status = Response::HTTP_FOUND): Response
    {
        return new Response('', $status, ['Location' => $url]);
    }
}
