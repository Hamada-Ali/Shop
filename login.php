<?php
ob_start();
session_start();
$title = "Login";
if (isset($_SESSION['user'])) {

    header("location:index.php");
}
include "init.php"; ?>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['saveme'])) {

        echo "<script>
        window.onload = function () {
            
                  $('.form-container .form-background').hide();
        $('.form-container .form-background-sign-up').show();  
        } 
</script>";
    }

    if (isset($_POST['login'])) {

        // Login Form

        $user = $_POST['username'];
        $pass = $_POST['pass'];
        $hashedpass = sha1($pass);

        $stmt = $con->prepare("SELECT username, password, UserID FROM users WHERE username = ? AND password = ?");
        $stmt->execute(array($user, $hashedpass));
        $getid = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0) {
            session_start();
            $_SESSION['user'] = $user;
            $_SESSION['userid'] = $getid['UserID'];
            header("location:index.php");
            exit();
        } else {

            echo "<script>alert('Sorry Password Or Username Not Correct')</script>";

        }
    } else {

        /* Sign-Up Form */

        $username = filter_var($_POST['user'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['the-email'], FILTER_VALIDATE_EMAIL);
        $password = sha1($_POST['password']);

        // Set An Array To Save Error Massages
        $formErrors = array();

        // Check If The Username Filed Filled Or Not
        if (empty($username)) {

            $formErrors[] = "Sorry The Username Field Must Be Filled";
        }
        // Check If The Username Characters Larger Than 4 Characters Or Not
        if (strlen($username) <= 4 && !empty($username)) {

            $formErrors[] = "Username Must Be Larger Than 4 Characters";

        }
        // Check If The Email Valid Or Not
        if ($email === FALSE) {

            $formErrors[] = "The Email Not Valid";

        } else {

            $sanitizedEmail = filter_var($_POST['the-email'], FILTER_SANITIZE_EMAIL);

        }
        // Check If The Passwords Passed Or Not
        if (isset($_POST['password']) && $_POST['password2']) {
            // Check If The User Choose A Hard Password
            if (strlen($_POST['password']) <= 5) {

                $formErrors[] = "Please Choose A Hard Password Must Be Larger Than 6 Characters";

            } else {
                // Hash The Password To be More Secure
                $password1 = sha1($_POST['password']);
                $password2 = sha1($_POST['password2']);
                // Check Matching Between The Two Passwords
                if ($password1 !== $password2) {
                    $formErrors[] = "Sorry The Passwords Must Be Matched";
                }
            }
        } else {

            $formErrors[] = "Sorry The Password Field Must Be Filled";

        }
        if (empty($formErrors)) {

            // Check If The Username Exist In Database
            $checkUser = check('username', 'users', $username);
            if ($checkUser === 1) {

                $formErrors[] = "Sorry The Username (" . $username . ") Exist In Database";

            } else {
                $stmt = $con->prepare("INSERT INTO users(username, password, email, logdate)VALUES(:user, :pass, :email, NOW())");
                $stmt->execute(array(

                    ":user" => $username,
                    ":pass" => $password,
                    ":email" => $sanitizedEmail
                ));
                $count = $stmt->rowCount();
                if ($count > 0) {

                    $successMsg = "You Registered In Our Website Successfully ";


                }
            }
        }

    }
}
?>
    <div class="container form-container">
        <div class="form-background">
            <h1 class="text-center">Login</h1>
            <form class='form-horizontal login-form' action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <input type="text" class="form-control" placeholder="Username" name="username"/>
                <input type="password" class="form-control" placeholder="Password" name="pass"/>
                <input type="submit" value="Login" class="form-button" name="login">
            </form>
            <span class="login-link pull-right">Sign-up</span>
        </div>
        <div class="form-background-sign-up">
            <h1 class="text-center">Sign-up</h1>
            <form class='form-horizontal' action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Username" required="required" name="user"
                           value="<?php if (isset($username)) {
                               echo $username;
                           } ?>"/>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Email" required="required" name="the-email"
                           value="<?php if (isset($email)) {
                               echo $email;
                           } ?>"/>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password"
                           name="password" required="required"/>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Repeat Password"
                           name="password2" required="required"/>
                </div>
                <input type="submit" value="Sign-up" class="form-button" name="saveme">
            </form>
            <span class="sign-up-link pull-right">Login</span>
        </div>
        <div class="text-center">
            <?php
            if (isset($successMsg)) {

                echo  "<div class='successMsg'>" . $successMsg . "</div>";

            }
            // If There Is Error Loop It Then Print It
            if (!empty($formErrors)) {
                foreach ($formErrors as $error) {

                    echo "<div class=' errorMsg'>" . $error . "</div>";
                }
            } ?>
        </div>
    </div>
<?php
include $tmp . "footer.php";
ob_end_flush();