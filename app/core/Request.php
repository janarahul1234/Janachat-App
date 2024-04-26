<?php

namespace App\core;

use App\core\utils\CORS;

class Request
{
    public function __construct()
    {
        CORS::config($_ENV['CORS_ORIGIN']);
    }

    public function getUrl(): string
    {
        switch ($_ENV['SERVER_MODE']) {
            case 'TESTING':
                $url = $_SERVER['QUERY_STRING'];
                $url = str_contains($url, 'url=') ? '/' . substr($url, 4) : '/';
                $pos = strpos($url, '&');

                return $pos ? substr($url, 0, $pos) : $url;

            case 'DISPLAY':
                $url = $_SERVER['REQUEST_URI'];
                $pos = strpos($url, '?');
                return $pos ? substr($url, 0, $pos) : $url;

            default:
                throw new \InvalidArgumentException('Invalid server mode');
        }
    }

    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isGet(): string
    {
        return $this->getMethod() === 'get';
    }

    public function isPost(): string
    {
        return $this->getMethod() === 'post';
    }

    public function getBody(): array
    {
        $data = [];

        if ($this->isGet()) {
            foreach ($_GET as $key => $value) {
                $data[$key] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($this->isPost()) {
            foreach ($_POST as $key => $value) {
                $data[$key] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $data;
    }
}
