<?php

namespace App\core;

use App\core\template_engine\Razer;

class Router
{
    private Razer $razer;
    private Request $request;
    private Response $response;
    private Route $route;

    private array $routes;
    private string $layout;

    public function __construct(Request $request, Response $response, Route $route)
    {
        $this->razer = new Razer();

        $this->request = $request;
        $this->response = $response;
        $this->route = $route;

        $this->routes = $this->route->getRoutes();
    }

    private static function routeParameter(string $routePath, string $requestUrl): array|bool
    {
        $routePathParts = explode('/', ltrim($routePath, '/'));
        $requestUrlParts = explode('/', trim($requestUrl, '/'));

        if (count($routePathParts) !== count($requestUrlParts)) {
            return false;
        }

        $params = [];

        foreach ($routePathParts as $key => $part) {
            if (strpos($part, '{') !== false) {
                $varname = trim($part, '{}');
                $params[$varname] = $requestUrlParts[$key];
            } elseif ($part !== $requestUrlParts[$key]) {
                return false;
            }
        }

        return $params;
    }

    public function getCallback(string $method, string $requestUrl): mixed
    {
        foreach ($this->routes as $route) {
            if ($route['path'] === $requestUrl && $route['method'] === $method) {
                return $route['callback'];
            }

            if ($this->routeParameter($route['path'], $requestUrl) && $route['method'] === $method) {
                return [$route['callback'], $this->routeParameter($route['path'], $requestUrl)];
            }
        }

        return false;
    }

    public function getRouteName(string $name): string
    {
        $names = $this->route->getNames();
        return $names[$name] ?? '';
    }

    public function resolve(): string
    {
        $method = $this->request->getMethod();
        $requestUrl = $this->request->getUrl();
        $callback = $this->getCallback($method, $requestUrl);

        if (!$callback) {
            $this->response->setStatusCode(404);
            $view = $this->routes['404'] ?? false;

            return $view ? $this->render($view) : '404 | Not found';
        }

        if (is_callable($callback)) {
            return call_user_func($callback);
        }

        if (is_callable($callback[0])) {
            return call_user_func_array($callback[0], $callback[1]);
        }

        if (str_contains($callback[0][0], 'App')) {
            $callback[0][0] = new $callback[0][0]();
            $this->layout = $callback[0][0]->layout ?? '';

            return call_user_func_array([$callback[0][0], $callback[0][1] ?? 'index'], $callback[1]);
        }

        if (str_contains($callback[0], 'App')) {
            $callback[0] = new $callback[0]();
            $this->layout = $callback[0]->layout ?? '';

            return call_user_func([$callback[0], $callback[1] ?? 'index']);
        }

        return $this->render($callback[0], $callback[1]);
    }

    public function render(string $filename, array $values = []): string
    {
        $this->razer->setLayout($this->layout);
        $this->razer->setView($filename, $values);

        return $this->razer->render();
    }
}
