<?php
session_start();

if ($_SESSION['userLoggedIn'] != true) {
    header("location: auth.php");
}
?>

<div class="header-area">
    <div class="container">
        <div class="row">
            <div class="col-3">
                <div class="logo">
                    <a href="index.php">To do <span> List </span></a>
                </div>
            </div>
            <div class="col-9">
                <div class="mainmenu">
                    <ul>
                        <li><a href="index.php?task=add">Add To do list</a></li>
                        <li><a href="index.php?task=toDoList">Show lists</a></li>
                        <li><a href="auth.php?logout=true"><?php echo $_SESSION['user']; ?>( logout )</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>