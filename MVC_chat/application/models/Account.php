<?php

namespace application\models;

use vendor\core\Model;

class Account extends Model {


    public $nameValidation = "/^[a-zA-Z0-9]*$/";

    public function findUserByEmail($email) {
        $count = $this->select()->where(['email' => $email])->rowCount();

        if($count > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function login($email, $password) {
        $row = $this->select()->where(['email' => $email])->fetch_accoc();

        if ($row) {
            $hashedPassword = $row["password"];
            if (password_verify($password, $hashedPassword)) {
                return $row;
            } else {
                return false;
            }
        }
        return false;
    }

    public function createUserSession($user) {
        $_SESSION['user_id'] = $user["id"];
        $_SESSION['first_name'] = $user["first_name"];
        $_SESSION['last_name'] = $user["last_name"];
        $_SESSION['email'] = $user["email"];
        if ($user["prof_img"]) {
            $_SESSION['prof_img'] = $user["prof_img"];
        }
        $_SESSION['success'] = array('mess' => 'You are logged in', 'registered' => time());
        header('location:' . URL_ROOT . '/');
    }



}