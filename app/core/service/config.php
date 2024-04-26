<?php

function serverMode() {
    switch ($_ENV['SERVER_MODE']) {
        case 'TESTING':
            return $_ENV['BASE_URL'];

        case 'DISPLAY':
            return $_ENV['ALT_BASE_URL'];

        default:
            throw new \InvalidArgumentException('Invalid server mode');
    }
}