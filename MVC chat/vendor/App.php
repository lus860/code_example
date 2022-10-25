<?php

include_once LIB."Dev.php";

use vendor\core\Router;

class App
{
    public static function run()
    {
        spl_autoload_register(function ($class) {
            $path = str_replace('\\', '/', dirname(__DIR__).DIRECTORY_SEPARATOR.$class . '.php');

            if (file_exists($path)) {
                require $path;
            }
        });

        $router = new Router;
        $router->run();
    }

}