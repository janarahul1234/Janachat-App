<?php

namespace App\core;

class Route
{
    private static array $routes = [];
    private static array $names = [];
    private static string $path = '';

    public function getRoutes(): array
    {
        return self::$routes;
    }

    public function getNames(): array
    {
        return self::$names;
    }

    private static function routeRegister(
        string $method,
        string $path,
        callable|array $callback
    ): void {
        self::$routes[] = [
            'path' => $path,
            'method' => $method,
            'callback' => $callback
        ];
    }

    public static function view(string $url, string $name, array $values = []): Route
    {
        self::$path = $url;
        self::routeRegister('get', $url, ['view' => $name, 'values' => $values]);

        return new static;
    }

    public static function get(string $url, callable|array $callback): Route
    {
        self::$path = $url;
        self::routeRegister('get', $url, $callback);

        return new static;
    }

    public static function post(string $url, callable|array $callback): void
    {
        self::routeRegister('post', $url, $callback);
    }

    public static function name(string $name): void
    {
        self::$names[$name] = self::$path;
    }

    public static function fallback(string $filename): void
    {
        self::$routes['404'] = $filename;
    }
}
