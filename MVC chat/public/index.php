<?php
include_once "../configs/constants.php";
$db = include_once "../configs/db.php";

define("CONFIG_DB",$db);
include_once VENDOR."App.php";
session_start();

function isLoggedIn() {
    if (isset($_SESSION['user_id'])) {
        return true;
    } else {
        return false;
    }
}
try{
    App::run();
}catch (Exception $exception){
    echo "<h3 align='center' >".$exception->getMessage()."</h3>";
    echo "<h3 align='center' > Throwed in ".$exception->getFile()."</h3>";
    echo "<h3 align='center' > On Line ".$exception->getLine()."</h3>";
    echo "<h1 align='center' > Error Code: ".$exception->getCode()."</h1>";
}


