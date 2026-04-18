<?php

declare(strict_types=1);

namespace App\Controller;

use App\Attribute\Route;
use App\Core\Controller\AbstractController;
use App\Core\Http\Request;
use App\Core\Http\Response;

final class SiteController extends AbstractController
{
    #[Route('/', methods: [Request::METHOD_GET], name: 'home')]
    public function index(): Response
    {
        return $this->render('site/index', [
            'title' => 'TODO List',
        ]);
    }

    #[Route('/{id}', methods: [Request::METHOD_GET], name: 'details')]
    public function details(int $id): Response
    {
        return $this->render('site/index', [
            'title' => 'ID: ' . $id,
        ]);
    }
}
