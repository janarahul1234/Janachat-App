<?php

function timestamp(): string
{
    date_default_timezone_set('Asia/Kolkata');
    return date('Y-m-d H:i:s');
}

function allFieldsPresent(array $keys, array $values): bool
{
    foreach ($keys as $key) {
        if (!array_key_exists($key, $values) || empty($values[$key])) {
            return false;
        }
    }

    return true;
}
