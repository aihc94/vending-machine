<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return static function (array $context) {
    try {
        return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
    } catch (\Throwable $exception) {
        $a = 1;
        return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
    }
    
};
