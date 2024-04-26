<?php

namespace App\core\utils;

class Cookies
{
    public static function isSetCookie(string $name): bool
    {
        return isset($_COOKIE[$name]);
    }

    public static function getCookie(string $name): mixed
    {
        return $_COOKIE[$name] ?? null;
    }

    public static function setCookie(
        string $name,
        string $value,
        int $expires = 0,
        string $domain = '',
        bool $secure = false,
        bool $httponly = false
    ): bool 
    {
        $expiresTime = $expires ? time() + (86400 * $expires) : 0; // 86400 seconds = 1 day
        return setcookie($name, $value, $expiresTime, '/', $domain, $secure, $httponly);
    }

    public static function deleteCookie(string $name): bool
    {
        return setcookie($name, '', time() - 2592000, '/');
    }
}
