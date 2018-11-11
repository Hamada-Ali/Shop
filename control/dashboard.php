<?php
ob_start();
session_start();
if (isset($_SESSION['username'])) {

    $title = "Home";

    include "init.php";

    // Start Dashboard
    ?>

    <div class="container dashboard text-center">
        <h1>Dashboard</h1>
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 text-center">
                <div class="group members">
                    <i class="fa fa-users"></i>
                    <div class="info">
                    Total Members
                    <a href="members.php"><?php echo check_item('UserID', 'users', false); ?></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 text-center">
                <div class="group pending">
                    <i class="fa fa-user-plus"></i>
                    <div class="info">
                    Pending Members
                    <a href="members.php?active=false"><?php echo check_item('UserID', 'users', true);?></a>
                </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 text-center">
                <div class="group items">
                    <i class="fa fa-tags"></i>
                    <div class="info">
                    Total Items
                    <a href="items.php"><?php ECHO  check_item("ID", "items") ?></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 text-center">
                <div class="group comments">
                    <i class="fa fa-comments-o"></i>
                    <div class="info">
                    Total Comments
                        <a href="comments.php"><?php echo  check_item("ID", "comments") ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    // Variables For Latest Users
    $latest_users = 6; // limit for users
    $users = get_latest('*', 'users', 'UserID', $latest_users); // Fetch All User Data To Use It
    $latest_items = 4;
    $items = get_latest('*', 'items', 'ID', $latest_items);
    $latest_comments = 5;
    $comments = get_latest('*', 'comments', 'ID', $latest_comments);
    ?>

    <div class="container latest">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-users"></i> Latest <?php echo $latest_users ?> Registered Users
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                        <?php
                            echo "<ul>";
                            foreach ($users as $user) {
                                echo "<li>" . $user['username'] . "<a class='btn btn-success pull-right' href='members.php?action=edit&id=$user[UserID]'><i class='fa fa-edit'></i> Edit</a>" . $active =  ($user['TrueStatus'] == 0) ? " <a href='members.php?action=activate&id=$user[UserID]' class='btn btn-info pull-right'><span class='glyphicon glyphicon-ok'></span> Activate</a>": null . "</li>";
                                }
                            echo "</ul>";
                             ?>
                        </div>
                </div>
              </div>
                <div class="col-md-6">
                    <div class="panel panel-default" >
                        <div class="panel-heading">
                            <i class="fa fa-tags"></i> Latest <?php echo $latest_items; ?> Items
                            <span class=" pull-right  toggle-info">
                            <i class="fa fa-plus pull-right"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <?php
                            echo "<ul>";
                            foreach ($items as $item) {
                                echo "<li>" . $item['name'] . "<a class='btn btn-success pull-right' href='items.php?action=edit&id=$item[ID]'><i class='fa fa-edit'></i> Edit</a>" . $active =  ($item['approve'] == 0) ? " <a href='items.php?action=approve&id=$item[ID]' class='btn btn-info pull-right'><span class='glyphicon glyphicon-ok'></span> Activate</a>": null . "</li>";
                            }
                            echo "</ul>";
                            ?>
                        </div>
                    </div>
                </div>
                <!-- Start Latest Comments -->
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-comments-o"></i> Latest <?php echo $latest_comments; ?> Comments
                            <span class=" pull-right  toggle-info">
                            <i class="fa fa-plus pull-right"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <?php
                            $stmt = $con->prepare("SELECT 
                                                                    comments.*, items.name 
                                                            AS 
                                                                    item_name, users.username
                                                            FROM 
                                                                    comments
                                                             INNER JOIN 
                                                                    items 
                                                             ON 
                                                                    items.ID = comments.item_id
                                                             INNER JOIN 
                                                                    users
                                                             ON
                                                                    users.UserID = comments.user_id
                                                             ORDER BY ID DESC
                                                             LIMIT 5
                                                ");
                            $stmt->execute();
                            $rows = $stmt->fetchAll();
                            foreach ($rows as $row) {
                                echo "<div class='comment-box'>";
                                echo "<a class='user-n' href='members.php?action=edit&id=$user[UserID]'>" . $row['username'] . "</a>";
                                echo "<p class='user-c'>" . $row['comment'] . "<a href='comments.php?action=edit&id=$row[ID]' class='btn-style pull-right btn-warning'><i class='fa fa-pencil'></i> Edit</a>" . "<a href='#' class='btn-style pull-right btn-danger'><i class='fa fa-trash-o'></i> Delete</a>" . "</p>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                    </div>
                </div> <!-- End Latest Comments -->
        </div>
    </div>

    <?php

    // End Dashboard

    include $tmp .  "footer.php";

} else {

    header('location: index.php');

    exit();

}

ob_end_flush();