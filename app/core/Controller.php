<?php

namespace App\core;

use App\core\Request;
use App\core\Response;
use App\core\utils\Json;

class Controller
{
    protected Request $request;
    protected Response $response;
    protected Json $json;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->json = new Json();
    }

    protected function redirect(string $url = ''): void
    {
        header('Location:' . serverMode() . $url);
    }
}