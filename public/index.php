<?php

declare(strict_types=1);

use App\Controller\ArticleController;
use App\Main\Database;
use App\Main\Router;
use App\Main\View;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;

require dirname(__DIR__) . '/vendor/autoload.php';

$view = new View(
    templateDir: dirname(__DIR__) . '/templates',
    varDir:      dirname(__DIR__) . '/var',
);

$pdo = Database::connect(
    require dirname(__DIR__) . '/config/database.php'
);

$categoryRepository = new CategoryRepository($pdo);
$postRepository = new PostRepository($pdo);
$articleController = new ArticleController($categoryRepository, $postRepository, $view);

$router = new Router();

$router->get('/', [$articleController, 'index']);
$router->get('/category/{slug}', [$articleController, 'category']);
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
