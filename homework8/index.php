<?php
if(!empty($_POST)){
    session_start();
    $_SESSION = $_POST;
    header("Location: views/user/");
}?>
<?php require_once realpath("views/site/header.php"); ?>
<?php require_once realpath("views/site/index.php"); ?>
<?php require_once realpath("views/site/footer.php"); ?>


