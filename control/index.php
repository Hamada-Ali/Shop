<?php
session_start();
$noNavBar = '';
$bk = '';
$title = 'Login';
if (isset($_SESSION['username'])) {
    header('location: dashboard.php');
}
include "init.php";

?>
<?php

    // Check If The Request Method Is a HTTP Post Request

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $user = $_POST['user'];
        $pass = $_POST['password'];
        $hashed_pass = sha1($pass); // Hashing The Password

        // Check If The User Is Admin And Exist In Database

        $stmt = $con->prepare("SELECT 
                                             UserID ,username, password 
                                         FROM 
                                             users 
                                         WHERE 
                                            username = ? 
                                         AND 
                                            password = ? 
                                         AND 
                                            GroupID = 1
                                         LIMIT 
                                             1");
        $stmt->execute(array($user, $hashed_pass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) {
          session_start();
            $_SESSION['username'] = $user;
            $_SESSION['ID'] = $row['UserID'];
            header('location:dashboard.php');

        }

    }
?>

<form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <h4 class="text-center">LOGIN</h4>
    <input type="text" name="user" class="form-control"  autocomplete="off" placeholder="username">
    <input type="password" name="password" class="form-control" autocomplete="off" placeholder="password">
    <input type="submit" value="login" class="btn btn-primary btn-block">
</form>


<?php include $tmp . "footer.php";?>