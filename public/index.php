<?php

declare(strict_types=1);

use App\Controller\ArticleController;
use App\Main\Database;
use App\Main\Router;
use App\Main\View;

require dirname(__DIR__) . '/vendor/autoload.php';

$view = new View(
    templateDir: dirname(__DIR__) . '/templates',
    varDir:      dirname(__DIR__) . '/var',
);

$pdo = Database::connect(
    require dirname(__DIR__) . '/config/database.php'
);

$router = new Router();

$router->get('/', [new ArticleController($pdo, $view), 'index']);
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
