<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Attribute\Route;
use App\Core\App;
use App\Core\Controller\AbstractController;
use App\Core\Http\Request;
use App\Core\Http\Response;

final class TaskController extends AbstractController
{
    #[Route('/api/tasks', methods: [Request::METHOD_POST], name: 'api.tasks.store')]
    public function store(): Response
    {
        $content = App::$request->getContent();
        $data = json_decode($content ?: '{}', true);
        $title = trim((string) ($data['title'] ?? ''));
        $description = $data['description'] ?? null;

        if ($title === '') {
            return $this->json([
                'ok' => false,
                'errors' => [
                    'title' => 'Title is required.',
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->json([
            'ok' => true,
            'task' => [
                'title' => $title,
                'description' => is_scalar($description) ? (string) $description : null,
                'is_completed' => false,
            ],
        ], Response::HTTP_CREATED);
    }
}
