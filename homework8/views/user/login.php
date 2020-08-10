<?php
require_once "verify.php";
if (isset($_COOKIE['chek']) && $_COOKIE['check'] == $val) {
    header('Location: page.php');
}
?>
<div class="page-wrapper bg-gra-01 p-t-180 p-b-100 font-poppins">
    <div class="wrapper wrapper--w780">
        <div class="card card-3">
            <div class="card-heading"></div>
            <div class="card-body">
                <h2 class="title">Login </h2>
                <form method="POST" action="<? echo $_SERVER['PHP_SELF']?>">
                    <div class="input-group">
                        <input class="input--style-3" type="email" placeholder="Email" name="email">
                    </div>
                    <div class="input-group">
                        <input class="input--style-3" type="password" placeholder="Password" name="password">
                    </div>
                    <div class="input-group">
                        <input class="input--style-3" type="checkbox" name="chek">

                    </div>
                    <div class="p-t-10">
                        <button class="btn btn--pill btn--green" type="submit" name="sub">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
