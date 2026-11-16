<?php

declare(strict_types=1);

use App\Controller\ArticleController;
use App\Main\Router;
use App\Main\View;

// Встроенный PHP-сервер: существующие файлы (css, картинки) отдаём напрямую.
if (PHP_SAPI === 'cli-server') {
    $file = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    if (is_file($file)) {
        return false;
    }
}

require dirname(__DIR__) . '/vendor/autoload.php';

$view = new View(
    templateDir: dirname(__DIR__) . '/templates',
    varDir:      dirname(__DIR__) . '/var',
);

$router = new Router();

$router->get('/', [new ArticleController($view), 'index']);
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
