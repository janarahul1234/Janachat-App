<?php

function autoload(string $directory): void
{
    $directory = trim($directory, '/');
    $files = glob(ROOT_DIR . "/{$directory}/*.php");

    if (!$files) return;

    foreach ($files as $filename) {
        require_once $filename;
    }
}
