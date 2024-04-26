<?php

namespace App\core\utils;

class CORS
{
    public static function config(string $cors_origin)
    {
        header('Access-Control-Allow-Origin: ' . $cors_origin);
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: X-Requested-With,Authorization,Content-Type');
        header('Access-Control-Max-Age: 86400');

        strtolower($_SERVER['REQUEST_METHOD']) === 'options' ? exit() : null;
    }
}
