<?php

namespace App\Main;

final class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = rtrim(parse_url($uri, PHP_URL_PATH) ?: '/', '/') ?: '/';

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            // паттерн для получения параметров из пути, по типу /article/{slug}
            $pattern = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $route) . '$#';

            if (preg_match($pattern, $path, $matches) !== 1) {
                continue;
            }

            $params = array_values(array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY));

            [$controller, $action] = $handler;
            $response = $controller->{$action}(...$params);

            echo $response;

            return;
        }
    
        http_response_code(404);
        echo '404 Not Found';
    }
}
