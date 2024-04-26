<?php

namespace App\Helpers;

use App\core\utils\Json;

class ApiError
{
    public static function send(int $code, string $message, mixed $details = null): string
    {
        $error = [
            'code' => $code,
            'message' => $message
        ];

        if ($details !== null) {
            $error['details'] = $details;
        }

        $json = new Json();
        return $json->send(['error' => $error]);
    }
}
