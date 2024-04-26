<?php

use Ramsey\Uuid\Uuid;

function generateUuid(): string
{
    return Uuid::uuid4()->toString();
}