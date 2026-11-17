<?php

declare(strict_types=1);

use App\Controller\ArticleController;
use App\Controller\CategoryController;
use App\Controller\HomeController;
use App\Main\Database;
use App\Main\Router;
use App\Main\View;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;

require dirname(__DIR__) . '/vendor/autoload.php';

session_start();

$view = new View(
    templateDir: dirname(__DIR__) . '/templates',
    varDir:      dirname(__DIR__) . '/var',
);

$pdo = Database::connect(
    require dirname(__DIR__) . '/config/database.php'
);

$categoryRepository = new CategoryRepository($pdo);
$postRepository = new PostRepository($pdo);

$homeController = new HomeController($categoryRepository, $view);
$categoryController = new CategoryController($categoryRepository, $postRepository, $view);
$articleController = new ArticleController($postRepository, $categoryRepository, $view);

$router = new Router();

$router->get('/', [$homeController, 'index']);
$router->get('/category/{slug}', [$categoryController, 'show']);
$router->get('/article/{slug}', [$articleController, 'show']);
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
