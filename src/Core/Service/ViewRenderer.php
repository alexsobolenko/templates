<?php

declare(strict_types=1);

namespace App\Core\Service;

use App\Core\App;
use App\Core\Http\Response;

final class ViewRenderer
{
    /**
     * @param string $view
     * @param array $params
     * @param int $status
     * @return Response
     */
    public function render(string $view, array $params = [], int $status = Response::HTTP_OK): Response
    {
        $viewsPath = App::$rootPath . '/views';
        $viewFile = $viewsPath . '/' . ltrim($view, '/') . '.php';

        if (!is_file($viewFile)) {
            return new Response('View not found', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        extract($params, EXTR_SKIP);

        ob_start();
        include $viewFile;
        $content = (string) ob_get_clean();

        ob_start();
        include $viewsPath . '/layout/main.php';
        $body = (string) ob_get_clean();

        return new Response($body, $status, ['Content-Type' => 'text/html; charset=utf-8']);
    }
}
