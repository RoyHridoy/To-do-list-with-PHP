<?php include_once "inc/functions.php"; ?>

<?php
session_start();

if (isset($_SESSION['userLoggedIn']) == true) {
    header("location: index.php");
}

if (isset($_POST['submit'])) {
    $fname       = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
    $lname       = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
    $email       = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password    = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $confirmPass = filter_input(INPUT_POST, 'confirmPassword', FILTER_SANITIZE_STRING);
    $term        = filter_input(INPUT_POST, 'term', FILTER_SANITIZE_STRING);

    if (isset($fname) && isset($lname) && isset($email) && isset($password) && isset($confirmPass) && isset($term)) {
        if (($password == $confirmPass) && !isDuplicateEmail($email)) {
            createAccount($fname, $lname, $email, $password);
            $successMessage = "You have successfully create your account.";
            $clearInput     = true;
        } elseif (isDuplicateEmail($email)) {
            $errorMessage = "Email address already exist.";
        } else {
            $errorMessage = "Both password doesn't match.";
        }
    } else {
        $errorMessage = "Please fill up all fields.";
    }
}
?>

<?php include_once "inc/templates/header.php"; ?>
    <pre>
<?php

?>
    </pre>
    <div class="login-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="content-box col-4 offset-4">
                    <h4 class="text-center text-uppercase mb-4">Sign up form</h4>

                    <?php if (isset($successMessage)) : ?>
                        <div class="alert alert-success" role="alert">
                            <strong> <?php echo $successMessage; ?> </strong>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($errorMessage)) : ?>
                        <div class="alert alert-warning" role="alert">
                            <strong> <?php echo $errorMessage; ?> </strong>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="fname">First Name</label>
                            <input type="text" name="fname" id="fname" class="form-control"
                                   value="<?php if (isset($fname) && !isset($clearInput)) {
                                       echo $fname;
                                   } ?>">
                        </div>

                        <div class="form-group">
                            <label for="lname">Last Name</label>
                            <input type="text" name="lname" id="lname" class="form-control"
                                   value="<?php if (isset($lname) && !isset($clearInput)) {
                                       echo $lname;
                                   } ?>">
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="text" name="email" id="email" class="form-control"
                                   value="<?php if (isset($email) && !isset($clearInput)) {
                                       echo $email;
                                   } ?>">
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password</label>
                            <input type="password" id="confirmPassword" name="confirmPassword" class="form-control">
                        </div>

                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="term" name="term"
                                   value="accepted"<?php if (isset($term) && !isset($clearInput)) {
                                echo "checked=\"checked\"";
                            } ?>">
                            <label class="custom-control-label" for="term">Agree to our term and condition</label>
                        </div>

                        <button type="submit" name="submit" class="btn btn-primary">Sign Up</button>
                    </form>
                    <h6>Already have account? <a href="auth.php">Sign in</a></h6>
                </div>
            </div>
        </div>
    </div>

<?php include_once "inc/templates/footer.php" ?>