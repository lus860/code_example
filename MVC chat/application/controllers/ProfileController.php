<?php

namespace application\controllers;

use vendor\core\Controller;
use application\models\Account;

class ProfileController extends Controller {

    const path = [
        'image' => 'uploads/',
        'profile' => IMAGES.'profile',
    ];


    public function indexAction() {

        $this->view->render('profile.index','Edit Profile');
    }

    public function editAction() {

        if (isset($_POST['submit'])) {
            $errors = array();
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $file_name = $_FILES['image']['name'];
                $file_size = $_FILES['image']['size'];
                $file_tmp = $_FILES['image']['tmp_name'];
                $file_type = $_FILES['image']['type'];
                $file_ext = explode('.', $_FILES['image']['name']);
                $file_ext = strtolower(end($file_ext));

                $extensions = array("jpeg","jpg","png");

                if (in_array($file_ext,$extensions) === false) {
                    $errors[] = "Extension not allowed, please choose a JPEG or PNG file.";
                }

                if ($file_size > 2097152) {
                    $errors[] = 'File size must be excately 2 MB';
                }


                $file_name = time() . '.' .$file_ext;
                if (empty($errors) == true) {
                    if (!is_dir(self::path['profile'])) {
                        mkdir(self::path['profile']);
                    }
                    move_uploaded_file($file_tmp, self::path['profile'].'\\'.$file_name);
                    $user = new Account();
                    $user->setTable('accounts');
                    $findUser = $user->find($_SESSION['user_id']);
                    $oldImg = $findUser['prof_img'];
                    if ($oldImg && file_exists(self::path['profile'].'\\'.$oldImg)) {
                        unlink(self::path['profile'].'\\'.$oldImg);
                    }
                    $user->where(['id' => $_SESSION['user_id']])->update(['prof_img'=> $file_name]);
                    $_SESSION['prof_img'] = $file_name;
                    $_SESSION['success'] = array('mess' => 'Your profile has been updated', 'registered' => time());
                    $this->view->redirect('/');
                } else {
                    return $this->view->render('profile.index','Edit Profile', ['errors' => $errors]);
                }
            } else {
                $_SESSION['errors'] = array('mess' => 'Image not found...!!!', 'registered' => time());            }
                $this->view->redirect('/profile');
        }

    }

    public function albumAction(){
        $accounts = new Account();
        $accounts->setTable('accounts');
        $users = $accounts->select()->fetch_obj();
        return $this->view->render('profile.album.index','Edit Profile',['users'=>$users]);

    }

}