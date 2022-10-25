<?php

namespace application\models;

use application\models\Account;
use PDO;
use vendor\core\Model;

class Message extends Model {

    public function selectChat($id) {

        $user_first = $this->select()
            ->where(['from' => $_SESSION['user_id'], 'to' => $id])
            ->fetch_obj();

        $user_second = $this->select()
            ->where(['from' => $id, 'to' => $_SESSION['user_id']])
            ->fetch_obj();
        return array_merge($user_first, $user_second);
    }

    public function updateStatus($id) {
        $this->where(['from' => $id, 'to' => $_SESSION['user_id']])
            ->update(['status'=>1]);
    }

    public function interlocutor($id) {
        $user = new Account();
        $user->setTable('accounts');
        return $user->find($id);

    }

}