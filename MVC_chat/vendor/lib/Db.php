<?php

namespace vendor\lib;

use PDO;

class Db {

    protected $db;

    public function __construct() {
        $config = CONFIG_DB;
        $this->db = new PDO('mysql:host='.$config['host'].';dbname='.$config['name'].'', $config['user'], $config['password']);
    }

}