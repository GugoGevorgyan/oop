<?php
session_start();
require_once '../site/header.php';
require_once "../../vendor/autoload.php";


use app\controllers\user\UserController;
$user_data = $_SESSION;
session_unset();
$user = new UserController();
if($user->register($user_data)){
    require_once "headrLocaton.php";
}else{
    echo 'Registration failed';
}


require_once '../site/footer.php';
