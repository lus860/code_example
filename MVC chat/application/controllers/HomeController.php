<?php

namespace application\controllers;

use vendor\core\Controller;
use application\models\Account;

class HomeController extends Controller {

	public function indexAction() {

	    $accounts = new Account();
        $accounts->setTable('accounts');
	    $users = $accounts->select()->fetch_obj();
		$this->view->render('main.index','Home page',['users'=>$users]);
	}

}