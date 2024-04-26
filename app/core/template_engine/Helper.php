<?php

namespace App\core\template_engine;

use App\core\template_engine\Injector;

class Helper
{
    private Injector $injector;

    public function __construct()
    {
        $this->injector = new Injector();
    }

    public function buffer(string $file, array $values = []): string
    {
        $file = ROOT_DIR . "/app/views/{$file}.php";

        if (!file_exists($file)) {
            return false;
        }

        extract($values);
        ob_start();
        include $file;
        return ob_get_clean();
    }

    public function compile(string $buffer, array $values = []): string
    {
        require_once 'functions.php';
        
        $buffer = $this->injector->commentInject($buffer);
        $buffer = $this->injector->variableInject($buffer, $values);
        $buffer = $this->injector->functionInject($buffer);

        return $buffer;
    }
}
