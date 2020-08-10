<?php
require_once "../../vendor/autoload.php";
$user = new \app\controllers\user\UserController();
 $val= '';

if (!empty($_POST['email']) && !empty($_POST['password'])){
    if($user->verifyLogin($_POST['email'],$_POST['password'])){
        session_start();
        $login = $_POST['email'];
        $pass =$_POST['password'];
        $salt = "iewsdjmzcm";
        $val = md5($pass.$login.$salt);
        $_SESSION['login'] = $login;
        $_SESSION['pass'] = $pass;
        if(isset($_POST['chek'])) {
            setcookie('chek', $val, time()+3600);
        } else {
            $_SESSION['logged'] = 'true';
        }
        header('Location: page.php');
    }else{
        header('Location: login.php');
    }

}


if(isset($_GET['token']) && isset($_GET['email'])){


    if($user->verify($_GET['token'],$_GET['email'])){
//        header('Location: login.php');
        require_once "login.php";
//        header('Location: views/user/login.php');
    }else{
        echo 'failed';
    }
}
?>