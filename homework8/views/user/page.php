<?php
session_start();

//if (!isset($_COOKIE['chek']) && !isset($_SESSION['logged'])) {
//    print_r($_SESSION);
//    session_destroy();
//
//    header('Location: verify.php');
//}
if (!empty($_POST["out"])) {
    if (isset($_POST["chb"]) && $_POST["chb"] === "logout") {
        session_unset();
        session_destroy();
        setcookie("chek", "", time() - 3600);
        unset($_COOKIE);
        header("location:login.php");
    }
    header("location:../../index.php");
//    else {
////    header("location:login.php");
//    }
}

echo "velcom to page uru glox";
?>
<form method="POST" action="<? echo $_SERVER['PHP_SELF']?>">
    <div class="p-t-10">
        <p>logout<input type="checkbox" name="chb" value="logout"></p>
        <button class="btn btn--pill btn--green" type="submit" name="out" value="out">logout</button>
    </div>
</form>

