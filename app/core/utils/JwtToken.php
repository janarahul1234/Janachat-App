<?php

namespace App\core\utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtToken
{
    public static function create(array $payload): string
    {
        return JWT::encode($payload, $_ENV['SECRET_KEY'], 'HS256');
    }

    public static function validate(string $jwt): array|bool
    {
        try {
            return (array)JWT::decode($jwt, new Key($_ENV['SECRET_KEY'], 'HS256'));
        } catch (\Exception $e) {
            return false;
        }
    }
}
