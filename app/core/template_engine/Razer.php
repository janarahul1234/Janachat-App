<?php

namespace App\core\template_engine;

use App\core\template_engine\Helper;

class Razer
{
    private string $view;
    private array $values;
    private string $layout;

    private Helper $helper;

    public function __construct()
    {
        $this->helper = new Helper();
    }

    public function setView(string $view, array $values = []): void
    {
        $this->view = $view;
        $this->values = $values;
    }

    public function setLayout(string $layout): void
    {
        $this->layout = "layouts/{$layout}";
    }

    private function parseXLayout(string $bufferFile): bool | array
    {
        $layoutPattern = '/<x-layout>(.*?)<\/x-layout>/is';

        if (!preg_match($layoutPattern, $bufferFile, $matches)) return false;

        $layoutContent = $matches[1];
        $slotPattern = '/<x-slot\s+name="([^"]+)">(.*?)<\/x-slot>/is';
        preg_match_all($slotPattern, $layoutContent, $slotMatches, PREG_SET_ORDER);

        $variables = [];
        $remainingText = $layoutContent;

        foreach ($slotMatches as $match) {
            $variables[$match[1]] = $match[2];
            $remainingText = str_replace($match[0], '', $remainingText);
        }

        $variables['slot'] = trim($remainingText);
        return $variables;
    }

    public function render(): string
    {
        $view = $this->helper->buffer($this->view);

        if (!$view) return "View file not found: {$this->view}";

        $view = $this->helper->compile($view, $this->values);
        $slots = $this->parseXLayout($view);

        if (!$slots) return $view;

        $layout = $this->helper->buffer($this->layout);

        if (!$layout) return "Layout file not found: {$this->layout}";

        return $this->helper->compile($layout, $slots);
    }
}
