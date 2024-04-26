<?php

use App\core\Application;
use App\core\template_engine\Helper;

function import(string $filename, array $values = []): string
{
    $helper = new Helper();
    $file = $helper->buffer($filename, $values);
    $file = $helper->compile($file, $values);

    return $file;
}

function route(string $name): string
{
    return serverMode() . Application::$app->router->getRouteName($name);
}

function asset(string $name): string
{
    return serverMode() . "/{$name}";
}
