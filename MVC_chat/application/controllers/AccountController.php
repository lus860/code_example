<?php

namespace application\controllers;

use vendor\core\Controller;

class AccountController extends Controller {

	public function loginAction() {

        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required', 'length'=> ['min'=> 8] ],
        ];

        $result = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
            ];

            $error = $this->validation($rules, $data);

            if (empty($error)) {
                $loggedInUser = $this->model->login($data['email'], $data['password']);

                if ($loggedInUser) {
                    $this->model->createUserSession($loggedInUser);
                } else {
                    $data['password_error'] = 'Password or email is incorrect. Please try again.';

                    return $this->view->render('account.login','Login',$data);
                }
            }
            $result = array_merge($error, $data);
        }

        $this->view->render('account.login','Login', $result);
	}

    public function registerAction() {

        $rules = [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique'],
            'password' => ['required', 'length'=> ['min'=> 8] ],
            'confirm_password' => ['required', 'confirmPassword'],
        ];

        $result = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
            ];

            $error = $this->validation($rules, $data);

            if (empty($error)) {
                unset($data['confirm_password']);
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                try {
                    $this->model->insert($data);
                    $_SESSION['success'] = array('mess' => 'Registration Successful. Please Login', 'registered' => time());

                    $this->view->redirect('/account/login');
                }catch (Exception $e){
                    $e->getMessage();

                }

            }
            $result = array_merge($error, $data);
        }
        $this->view->render('account.register','Register', $result);
    }

    public function validation($rules, $data)
    {
        $error = [];
        foreach ($rules as $key => $val) {
            $newVal = implode(" ", explode("_", $key));
            if(in_array('required',$val)) {
                if (empty($data[$key])) {
                    $error[$key.'_error'] = "Please enter $newVal.";
                }
            }

            if(in_array('string',$val)) {
                if (!empty($data[$key]) && !preg_match($this->model->nameValidation, $data[$key])) {
                    $error[$key.'_error'] = ucfirst($newVal). " can only contain letters and numbers.";
                }
            }

            if(in_array('email',$val)) {
                if (!empty($data[$key]) && !filter_var($data[$key], FILTER_VALIDATE_EMAIL)) {
                    $error[$key.'_error'] = 'Please enter the correct format.';
                }
            }

            if(in_array('unique',$val)) {
                if (!empty($data[$key]) && $this->model->findUserByEmail($data[$key])) {
                    $error[$key.'_error'] = ucfirst($newVal). ' is already taken.';
                }
            }

            if(array_key_exists('length', $val)) {
                if (!empty($data[$key]) && isset($val['length']['min'])) {
                    if (strlen($data[$key]) < $val['length']['min']) {
                        $error[$key.'_error'] = ucfirst($newVal). ' must be at least '. $val['length']['min'] .' characters';
                    }
                }
                if (!empty($data[$key]) && isset($val['length']['max'])) {
                    if (strlen($data[$key]) > $val['length']['max']) {
                        $error[$key.'_error'] = ucfirst($newVal). ' must be no more than '. $val['length']['max'] .' characters';
                    }
                }

            }

            if(!empty($data[$key]) && in_array('confirmPassword',$val)) {
                if ($data['password'] != $data[$key]) {
                    $error[$key.'_error'] = 'Passwords do not match, please try again.';
                }
            }

        }
        return $error;
    }

    public function logoutAction() {
        unset($_SESSION['user_id']);
        unset($_SESSION['first_name']);
        unset($_SESSION['last_name']);
        unset($_SESSION['email']);
        unset($_SESSION['prof_img']);
        $this->view->redirect('/account/login');
    }

}