<?php

namespace vendor\core;

use vendor\core\View;

abstract class Controller {

	public $route;
	public $view;
	public $model;
    public $acl;

	public function __construct($route)
    {
		$this->route = $route;
        $this->view = new View();
        $this->checkAcl();
		$this->model = $this->loadModel($route['controller']);
		$this->unsetSession();
	}

	public function loadModel($name)
    {
		$path = 'application\models\\'.ucfirst($name);
		if (class_exists($path)) {
			return new $path($this->route);
		}
	}

    public function unsetSession()
    {
        if (isset($_SESSION['success'])) {
            if ((time() - $_SESSION['success']['registered']) > (5)) {
                unset($_SESSION['success']);
            }
        }

        if (isset($_SESSION['errors'])) {
            if ((time() - $_SESSION['errors']['registered']) > (5)) {
                unset($_SESSION['errors']);
            }
        }
    }

    public function checkAcl()
    {
        $this->acl = require BASE_URL.'/configs/acl/'.$this->route['controller'].'.php';

        if (isset($_SESSION['user_id']) and $this->isAcl('authorize')) {
            $this->view->redirect('/');
        }
        elseif (!isset($_SESSION['user_id']) and $this->isAcl('guest')) {
            $this->view->redirect('/account/login');
        }
//
//        return false;
    }

    public function isAcl($key) {
        return in_array($this->route['action'], $this->acl[$key]);
    }


}