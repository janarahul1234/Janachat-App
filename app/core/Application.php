<?php

namespace App\core;

use App\core\Request;
use App\core\Response;
use App\core\Route;
use App\core\Router;

class Application
{
    public static Application $app;

    private Request $request;
    private Response $response;
    private Route $route;
    public Router $router;

    public function __construct()
    {
        self::$app = $this;

        $this->request = new Request();
        $this->response = new Response();
        $this->route = new Route();

        $this->router = new Router($this->request, $this->response, $this->route);
    }

    public function run(): void
    {
        echo $this->router->resolve();
    }
}
