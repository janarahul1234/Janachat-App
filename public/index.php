<?php

define('ROOT_DIR', dirname(__DIR__));

require_once ROOT_DIR . '/vendor/autoload.php';
require_once ROOT_DIR . '/app/core/autoload.php';

use App\core\utils\Env;
use App\core\Application;

autoload('/app/core/service');
autoload('/routes');

Env::loadDotEnv();

$app = new Application();
$app->run();
