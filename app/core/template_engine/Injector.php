<?php

namespace App\core\template_engine;

class Injector
{
    public function commentInject(string $buffer): string
    {
        $pattern = '/\{\{\-\-(.*?)\-\-\}\}/';
        preg_match_all($pattern, $buffer, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $match) {
            $buffer = str_replace($match[0], '', $buffer);
        }

        return $buffer;
    }

    public function variableInject(string $buffer, array $variables): string
    {
        $pattern = '/\{\{\s*\$(.*?)\s*\}\}/';
        preg_match_all($pattern, $buffer, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $match) {
            $variable = trim($match[1]);
            $buffer = str_replace($match[0], $variables[$variable] ?? '', $buffer);
        }

        return $buffer;
    }

    public function functionInject(string $buffer): string
    {
        $pattern = '/\{\{\s*@(.*?)\((.*?)\)\s*\}\}/';
        preg_match_all($pattern, $buffer, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $function = trim($match[1]);
            $args = trim($match[2]);
            $output = '';
            
            if (function_exists($function)) {
                $output = $this->functionExecut($function, $args);
            }

            $buffer = str_replace($match[0], $output, $buffer);
        }

        return $buffer;
    }

    private function functionExecut(callable $function, string $args = ''): string
    {
        $args = explode(',', $args);
        $variables = [];

        foreach ($args as $arg) {
            $variables[] = eval("return $arg;");
        }

        return call_user_func_array($function, $variables);
    }
}