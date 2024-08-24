<?php

namespace Autoloader;

class Autoloader
{
    public static function autoload()
    {
        spl_autoload_register(function ($class) {
            $file = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
            if (file_exists($file)) {
                require $file;
            } else {
                echo "File not found: $file<br>";
            }
        });
    }

}