<?php

use App\core\Application;

function view(string $name, array $values = []): string
{
    return Application::$app->router->render($name, $values);
}