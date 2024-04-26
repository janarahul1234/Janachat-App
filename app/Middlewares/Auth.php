<?php

namespace App\Middlewares;

use App\core\utils\Cookies;
use App\core\utils\JwtToken;

class Auth
{
    public static function verifyUser(): mixed
    {
        $jwt = '';
        
        if (Cookies::isSetCookie('Authorization')) {
            $jwt = Cookies::getCookie('Authorization');
        }

        return JwtToken::validate($jwt);
    }
}