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
        try {
            $body = $this->renderContent($view, $params);
        } catch (\RuntimeException) {
            return new Response('View not found', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new Response($body, $status, ['Content-Type' => 'text/html; charset=utf-8']);
    }

    /**
     * @param string $view
     * @param array $params
     * @param string|null $layout
     * @return string
     */
    public function renderContent(string $view, array $params = [], ?string $layout = 'layout/main'): string
    {
        $viewsPath = App::$rootPath . '/views';
        $content = $this->renderFile($viewsPath, $view, $params);
        if ($layout === null) {
            return $content;
        }

        return $this->renderFile($viewsPath, $layout, array_merge($params, [
            'content' => $content,
        ]));
    }

    /**
     * @param string $viewsPath
     * @param string $view
     * @param array $params
     * @return string
     */
    private function renderFile(string $viewsPath, string $view, array $params): string
    {
        $viewFile = $viewsPath . '/' . ltrim($view, '/') . '.php';
        if (!is_file($viewFile)) {
            throw new \RuntimeException('View not found: ' . $view . '.');
        }

        extract($params, EXTR_SKIP);

        ob_start();
        include $viewFile;

        return (string) ob_get_clean();
    }
}
