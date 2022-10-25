<?php

namespace vendor\core;

use vendor\core\View;

class Router {

    protected $routes = [];
    protected $params = [];

    public function __construct() {
        $arr = require ROUTES.'web.php';
        foreach ($arr as $key => $val) {
            $this->add($key, $val);
        }
    }

    public function add($route, $params) {
        $route = '#^'.$route.'$#';
        $this->routes[$route] = $params;
    }

    public function match() {
        $url = trim($_SERVER['REQUEST_URI'], '/');
        $query = parse_url($url, PHP_URL_QUERY);
        $url = parse_url($url, PHP_URL_PATH);
        if ($query) {
            $item_query = explode("&", $query);
            foreach ($item_query as $val) {
                $param = explode("=", $val);
                $_GET[$param[0]] = $param[1];
            }
        }
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                $this->params = $params;
                return true;
            }
        }

        return false;
    }

    public function run(){
        if ($this->match()) {
            $path = 'application\controllers\\'.ucfirst($this->params['controller']).'Controller';
            require CONTROLLERS.ucfirst($this->params['controller']).'Controller.php';
            if (class_exists($path)) {
                $action = $this->params['action'].'Action';
                if (method_exists($path, $action)) {

                    $controller = new $path($this->params);
                    $controller->$action();

                } else {
                    View::errorCode(404);
                }
            } else {
                View::errorCode(404);
            }
        } else {
            View::errorCode(404);
        }
    }

}