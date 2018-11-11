<?php
ob_start();
session_start();
$title = "Profile";
include "init.php";
if (isset($_SESSION['user'])) {

    $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute(array($usersession));
    $count = $stmt->rowCount();
    $fetch = $stmt->fetch();
        ?>
        <h1 class="text-center">My Profile</h1>
        <div class="information block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Profile Information
                    </div>
                    <div class="panel panel-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <i class="fa fa-lock"></i>
                                <span>Login Name</span> :  <?php echo $fetch['username'];?>
                            </li>
                            <li class="list-group-item">
                                <i class="fa fa-envelope-o"></i>
                                <span>Email</span> :  <?php echo $fetch['Email'];?>
                            </li>
                            <li class="list-group-item">
                                <i class="fa fa-user"></i>
                                <span>Full Name</span> :  <?php echo $fetch['FullName'];?>
                            </li>
                            <li class="list-group-item">
                                <i class="fa fa-calendar"></i>
                                <span>Register Date</span> :  <?php echo $fetch['logdate'];?>
                            </li>
                            <li class="list-group-item">
                                <i class="fa fa-tags"></i>
                                <span>Favourite Category</span> :
                            </li>
                        </ul>
                        <a href="#" class="btn btn-default">Edit Information</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="my-ads block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <?php echo  $usersession . " Ads" ?>
                    </div>
                    <div class="panel panel-body">
                        <?php
                        $items = showitems("member_id", $fetch['UserID'], 1);
                        if (! empty($items)) {
                            foreach ($items as $item) {

                                echo "<div class='col-xs-12 col-sm-6 col-md-4 col-lg-3'>";
                                echo "<div class='thumbnail item-box'>";
                                echo "<span class='price'>$" . $item['price'] . "</span>";
                                if ($item['approve'] == 0) {echo "<span class='approve-msg'>Waiting Approving</span>";}
                                echo "<img src='img2.png' alt='Image Here' class='img-responsive'/>";
                                echo "<div class='caption'>";
                                echo "<a href='showitems.php?id=$item[ID]' class='h3' style='color: #2e6da4'>$item[name]</a>";
                                echo "<p>" . $item['description'] . "<p>";
                                echo "<span class='pull-right item-date'>" . $item['date'] . "</span>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                            }
                        } else {

                            echo 'There\'s No Ads Here Ad New Ad ' . '<a href="newad.php">Here</a>';

                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="comments block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Latest Comments
                    </div>
                    <div class="panel panel-body">
                        <?php
                        $stmt = $con->prepare("SELECT comments.*, items.name FROM comments 
                                INNER JOIN items
                                ON 
                                comments.item_id = items.ID
                                 WHERE user_id = ?");
                        $stmt->execute(array($fetch['UserID']));
                        $comments =$stmt->fetchAll();
                        if (!empty($comments)) {
                            echo "<div class='col-md-3'>";
                            echo "item:";
                            echo "</div>";
                            echo "<div class='col-md-9'>";
                            echo "comment:";
                            echo "</div>";
                            // Print All Comments And Items Names
                            foreach ($comments as $comment) {
                                echo "<div class='col-md-3'>";
                                    echo $comment['name'];
                                echo "</div>";
                                echo "<div class='col-md-9'>";
                                    echo $comment['comment'];
                                echo "</div>";
                            }
                        } else {

                            echo 'There\'s No Comments';

                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
} else {

    header("location: login.php");

}
include $tmp . "footer.php";
ob_end_flush();