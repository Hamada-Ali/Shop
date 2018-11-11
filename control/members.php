<?php

/*
 ==============================================
 ================= Manage Page ================
 = You Can Edit, Delete, Update, Add Members. =
 ==============================================
 */

session_start();
// Title For The Member Page
$title = "Member";

// Check If The user Enter To The Page By The Right Way Not Directly
if (isset($_SESSION['username'])) {

    include "init.php";
    $action = '';
    // Manage Other Links Inside The Page
    if (isset($_GET['action'])) {

        $save = $_GET['action'];

    } else {

        $save = 'manage';

    }

    if ($save == "manage") {

        $active = "";

        if (isset($_GET['active']) && $_GET['active'] == "false") {

            $active = "AND TrueStatus = 0";

        } else {

            $active = "";

        }
        $stmt = $con->prepare("SELECT * FROM users  WHERE GroupID != 1 $active  ORDER BY UserID DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        ?>

        <!-- Manage Page -->
        <h1 class="text-center" style="margin: 25px 0">Manage Member</h1>
        <div class="container">
            <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead>
                <tr>
                    <th>#ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Full Name</th>
                    <th>Register Date</th>
                    <th>Control</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($rows as $row) {?>
                    <tr>
                        <td><?php echo $row['UserID'] ?></td>
                        <td><?php echo $row['username'] ?></td>
                        <td><?php echo $row['Email'] ?></td>
                        <td><?php echo $row['FullName'] ?></td>
                        <td> <?php echo $row['logdate'];?></td>
                        <td>
                            <a class="btn btn-warning" href="?action=edit&id=<?php echo $row['UserID']?>"><i class="fa fa-edit"></i>  Edit</a>
                            <a class="btn btn-danger confirm" href="?action=delete&id=<?php echo $row['UserID'] ?>"><i class="fa fa-close"></i> Delete</a>
                            <?php $active =  ($row['TrueStatus'] == 0) ? "<a href='?action=activate&id=$row[UserID]' class='btn btn-info'><span class='glyphicon glyphicon-ok'></span> Activate</a>" : null; echo $active;?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            </div>
            <a href='?action=add' class='btn btn-success'><i class='fa fa-plus'></i>  Add Member</a>
        </div>

    <?php } elseif ($save == "edit") {    // Start The Edit Page

        // Check If The Id Not Empty And Numeric
        $checkid = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;
        // select all data from user table
        $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
        // Execute Query
        $stmt->execute(array($checkid));
        // Fetch The Data From Database To Use It In The Form Members
        $row = $stmt->fetch();
        // The Row Count
        $count = $stmt->rowCount();
        // If $count > 0 IF user id Found In Database And Show The Form After That
        if ($count > 0) { ?>
            <!-- HTML code -->
            <!-- Design Members Form -->
            <h1 class="text-center members-h1">Edit Member</h1>
            <div class="container">
                <form class="form-horizontal members-form" action="?action=update" method="post">
                    <input type="hidden" value="<?php echo $row['UserID'] ?>" name="id">
                    <!-- User Section -->
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2 col-lg-2 unique" for="username">Username</label>
                        <div class="col-sm-8 col-md-10 col-lg-8">
                            <input type="text" class="form-control" id="username" name="username" autocomplete="off"
                                   value="<?php echo $row['username']; ?>" required="required">
                        </div>
                    </div>
                    <!-- Password Section -->
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2 col-lg-2" for="Password">Password</label>
                        <div class="col-sm-8 col-md-10 col-lg-8">
                            <input type="Password" class="form-control placeholder" id="Password" name="password"
                                   autocomplete="off" placeholder="Leave The Field Blank If You Don't Want Make Changes">
                        </div>
                    </div>
                    <!-- Email Section -->
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2 col-lg-2" for="Email">Email</label>
                        <div class="col-sm-8 col-md-10 col-lg-8">
                            <input type="email" class="form-control" id="Email" name="Email"
                                   value="<?php echo $row['Email']; ?>" required="required">
                        </div>
                    </div>
                    <!-- Full Name Section -->
                    <div class="form-group">
                        <label class="control-label col-sm-2 col-md-2 col-lg-2" for="Full">Full Name</label>
                        <div class="col-sm-8 col-md-10 col-lg-8">
                            <input type="text" class="form-control" id="Full" name="Full"
                                   value="<?php echo $row['FullName']; ?>" required="required">
                        </div>
                    </div>
                    <!-- Button -->
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="submit" class="btn btn-primary center-block" value="SAVE">
                        </div>
                    </div>

                </form>
            </div>
            <?php
        } else {

            redirect( "There Is No Such ID", 'danger', 'back', TRUE, 2); // if For Show The User Data
        }
}elseif ($save == "update") {
        // Check If The Request Method iS a Post Request
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Password Update If
            if (empty($_POST['password'])) {
                // Don't anything
            } else {
                $password = sha1($_POST['password']);
                $stmt = $con->prepare('UPDATE users SET password = ?');
                $stmt->execute(array($password));
                $passcheck = $stmt->rowCount();
            }

            // Set name As Values To Use It In Update The Information User
             $id = $_POST['id'];
            $user = $_POST['username'];
            $email = $_POST['Email'];
            $Full = $_POST['Full'];

            $formErrors = array(); // Set Variable To Save Errors Inside It
            // Check If The Fields Not Empty
            echo "<div class='container'>";
            // start username
            if (strlen($user) < 3 && !empty($user)) {

                $formErrors[] = "Sorry <strong>Username Must</strong> Be Larger Than <strong>3 Characters</strong>";

            }

            if (empty($user)) {

                $formErrors[] = "Sorry <strong>username</strong> Filed Can't Be <strong>Empty</strong>";

            }

            // End username
            // start Full Name
            if (strlen($Full) < 6 && !empty($Full)) {

                $formErrors[] = "Sorry <strong>Full Name</strong> Must Be Larger Than <strong>6 Characters</strong>";

            }

            if (empty($Full)) {

                $formErrors[] = "Sorry <strong>Full Name</strong> Filed Can't Be <strong>Empty</strong>";

            }
            // End username
            // Start Email
            if (empty($email)) {

                $formErrors[] = "Sorry <strong>Email</strong> Filed Can't Be <strong>Empty</strong>";

            }

            // Loop The Errors If Exists By Variable $formErrors
            foreach ($formErrors as $error) {

                redirect( $error , 'danger', 'back', TRUE, 3);

            }

            // Check If No Errors In $formErrors Array
            if (empty($formErrors)) {
                $stmt2 = $con->prepare("SELECT * FROM users WHERE username = ? AND UserID != ?");
                $stmt2->execute(array($user, $id));
                $count2 = $stmt2->rowCount();
                if ($count2 == 0) {
                $stmt = $con->prepare("UPDATE users SET username= ?, Email = ?, FullName = ? WHERE UserID = ?");
                $stmt->execute(array($user, $email, $Full, $id));
                $count = $stmt->rowCount();
                if ($count > 0 ) {

                    redirect($count . " Record Updated Successfully ", 'success','back',TRUE,3);

                } else {

                    redirect( " Nothing Updated", 'danger', 'back', TRUE, 3);
                }
                } else {

                    redirect( "Sorry Username Booked Up", 'danger', 'back', TRUE, 3);
                }

            }
        } else {

            redirect('ERROR 404', 'danger','back',TRUE,3);

        }
        // End The Edit Page
    }  elseif ($save == "add") { // Adding Page
        ?>
        <h1 class="text-center members-h1">Add Member</h1>
        <div class="container">
            <form class="form-horizontal members-form" action="?action=insert" method="post" enctype="multipart/form-data">
                <!-- User Section -->
                <div class="form-group">
                    <label class="control-label col-sm-2 col-md-2 col-lg-2 unique" for="username">Username</label>
                    <div class="col-sm-8 col-md-10 col-lg-8">
                        <input type="text" class="form-control" id="username" name="username" autocomplete="off"
                               required="required" placeholder="Add Username">
                    </div>
                </div>
                <!-- Password Section -->
                <div class="form-group">
                    <label class="control-label col-sm-2 col-md-2 col-lg-2" for="Password">Password</label>
                    <div class="col-sm-8 col-md-10 col-lg-8">
                        <input type="Password" class="form-control placeholder password" id="Password" name="password"
                               autocomplete="off" placeholder="Create a Hard Password" required="required">
                        <i class="fa fa-eye show-me"></i>
                    </div>
                </div>
                <!-- Email Section -->
                <div class="form-group">
                    <label class="control-label col-sm-2 col-md-2 col-lg-2" for="Email">Email</label>
                    <div class="col-sm-8 col-md-10 col-lg-8">
                        <input type="email" class="form-control" id="Email" name="Email"
                               required="required" autocomplete="off" placeholder="Create an Email">
                    </div>
                </div>
                <!-- Full Name Section -->
                <div class="form-group">
                    <label class="control-label col-sm-2 col-md-2 col-lg-2" for="Full">Full Name</label>
                    <div class="col-sm-8 col-md-10 col-lg-8">
                        <input type="text" class="form-control" id="Full" name="Full"
                               required="required" autocomplete="off" placeholder="Write Your Full Name">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2 col-md-2 col-lg-2" for="img">Your Image:</label>
                    <div class="col-sm-8 col-md-10 col-lg-8">
                        <input type="file" id="img" name="img">
                    </div>
                </div>
                <!-- Button -->
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="submit" class="btn btn-primary center-block" value="ADD">
                    </div>
                </div>

            </form>
        </div>
        <?php
    }  elseif ($save == "insert") {
       // Check If The Request Method iS a Post Request
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = $_POST['username'];
            $pass = sha1($_POST['password']);
            $email = $_POST['Email'];
            $Full = $_POST['Full'];
            $img = $_FILES['img'];

            // Image Attributes
            $imgName = $img['name'];
            $imgSize = $img['size'] ;
            $imgTmp = $img['tmp_name'] ;
            $imgType = $img['type'] ;

             // Allowed Extensions
            $allowedExtensions = array("jpeg", "jpg", "png", "gif");

            $imgExtension = strtolower(end(explode('.', $imgName)));


        }

            $formErrors = array(); // Set Variable To Save Errors Inside It
            // Check If The Fields Not Empty
            echo "<div class='container'>";
            // start username
            if (strlen($user) < 3 && !empty($user)) {

                $formErrors[] = "Sorry <strong>Username Must</strong> Be Larger Than <strong>3 Characters</strong>";

            }

            if (empty($user)) {

                $formErrors[] = "Sorry <strong>username</strong> Filed Can't Be <strong>Empty</strong>";

            }

            // End username
            // start Full Name
            if (strlen($Full) < 6 && !empty($Full)) {

                $formErrors[] = "Sorry <strong>Full Name</strong> Must Be Larger Than <strong>6 Characters</strong>";

            }

            if (empty($Full)) {

                $formErrors[] = "Sorry <strong>Full Name</strong> Filed Can't Be <strong>Empty</strong>";

            }
            // End username
            // Start Email
            if (empty($email)) {

                $formErrors[] = "Sorry <strong>Email</strong> Filed Can't Be <strong>Empty</strong>";

            }

            if (empty($pass)) {

                $formErrors[] = "Sorry Password Filed Must Be Fill";

            }

            if (!empty($imgName) && ! in_array($imgExtension, $allowedExtensions)) {

                $formErrors[] = "Please Upload A Valid Image";

            }

            if (empty($imgName)) {

                $formErrors[] = "Image Required";

            }

            global $imgSize;

            if ($imgSize > 4000000) {

                $formErrors[] = "The Maximum Size For The Image Is 4MB";

            }

            // Loop The Errors If Exists By Variable $formErrors
            foreach ($formErrors as $error) {

                redirect( $error, 'danger', 'back', TRUE, 4);

            }

           if (empty($formErrors)) {
               // Check If The Username Booked Up Or Not
               if (check('username', 'users', $user) == 0) {
                    // Move The Image To Uploads File
                   global $imgName;
                   global $imgTmp;
                   $theImg = rand(1, 9999999) . "_" . $imgName;
                   move_uploaded_file($imgTmp, "uploads/" . $theImg);

                   // Add Member To Database
                   $stmt = $con->prepare("INSERT INTO users(username, password, Email, FullName , logdate, image) VALUES (:user, :pass, :email, :full , now(), :image)");
                   $stmt->execute(array(
                       ":user" => $user,
                       ":pass" => $pass,
                       ":email" => $email,
                       ":full" => $Full,
                       ":image" => $theImg
                   ));
                   $count = $stmt->rowCount();
                   redirect( $count . " Record Added Successfully", 'success', 'back', TRUE, 4);

                   } else {

                   redirect( 'Sorry The Username Booked Up', 'danger', 'back', TRUE, 4);

               }

        } else {redirect( 'ERROR 404', 'danger', 'back', TRUE, 3);}

        } elseif ($save == "delete") {
            // Check If The Id Not Empty And Numeric
            $checkid = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;
            // Check If The Member ID Exists Or Not
            $check = check('UserID', 'users', $checkid);
        if ($check > 0) {

            $stmt = $con->prepare("DELETE FROM USERS WHERE UserID = :user");
            $stmt->bindParam('user' ,$checkid);
            $stmt->execute();
            $count = $stmt->rowCount();
            redirect( $count . " Record Deleted Successfully", 'success', 'back', TRUE, 4);
        } else {

            redirect( "There Is No Such ID", 'danger', 'back', TRUE, 3);
        }

    }   elseif ($save == "activate") {

        // Check If The Id Not Empty And Numeric
        $checkid = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;

        $check = check('UserID', 'users', $checkid);
        if ($check > 0) {

            $stmt = $con->prepare("UPDATE users SET TrueStatus = 1 WHERE UserID = ?");
            $stmt->execute(array($checkid));
            $count = $stmt->rowCount();
            if ($count > 0) {

                redirect( "The Member Has Been Activated", 'success', 'back', TRUE, 3);

            }
            /*$stmt = $con->prepare("DELETE FROM USERS WHERE UserID = :user");
            $stmt->bindParam('user' ,$checkid);
            $stmt->execute();
            $count = $stmt->rowCount();*/

        } else {

            redirect( "Member Not Exists", 'danger', 'back', TRUE, 3);

        }

    } else { // If User Set A value For $save And Not Found

        redirect('ERROR 404', 'danger','',TRUE,3);

    }

        include $tmp . "footer.php";

} else {

    header('location: index.php');

    exit();
}
