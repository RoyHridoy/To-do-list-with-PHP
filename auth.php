<?php include_once "inc/functions.php"; ?>

<?php
session_start();

if (isset($_SESSION['userLoggedIn']) == true) {
    header("location: index.php");
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    if (isValidUser($_POST['username'], $_POST['password'])) {
        $_SESSION['user']         = $_POST['username'];
        $_SESSION['userLoggedIn'] = true;
        header("location: index.php");
    } else {
        $errorMessage = "username and password doesn't match.";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("location: auth.php");
}


?>

<?php include_once "inc/templates/header.php"; ?>

    <div class="login-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="content-box col-4 offset-4">
                    <h4 class="text-center text-uppercase mb-4">login form</h4>
                    <?php if (isset($errorMessage)) : ?>
                        <div class="alert alert-danger" role="alert">
                            <strong><?php echo $errorMessage; ?></strong>
                        </div>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-control">
                        </div>

                        <button type="submit" name="submit" class="btn btn-primary">Login</button>
                    </form>

                    <h6>Don't have account? <a href="signup.php">Sign up</a></h6>
                </div>
            </div>
        </div>
    </div>

<?php include_once "inc/templates/footer.php" ?>