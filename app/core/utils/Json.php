<?php

namespace App\core\utils;

class Json
{
    public function send(array $values): string
    {
        return json_encode($values, true);
    }

    public function read(): array
    {
        return (array) json_decode(file_get_contents('php://input')) ?? [];
    }
}
