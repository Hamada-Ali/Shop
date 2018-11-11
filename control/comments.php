<?php

/*

 ==============================================
 ================= Comments Page ==============
 === You Can Edit, Delete, Approve Comments ===
 ==============================================

 */
ob_start();
session_start();
// Title For The Member Page
$title = "Comments";

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

        $stmt = $con->prepare("SELECT comments.*, items.name AS item_name, users.username
		                                FROM comments
                                         INNER JOIN 
                                         items 
                                         ON 
                                         items.ID = comments.item_id
                                         INNER JOIN 
                                         users
                                         ON
                                         users.UserID = comments.user_id
                                         ORDER BY ID DESC
                                                ");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        ?>

        <!-- Manage Page -->
        <h1 class="text-center" style="margin: 25px 0">Manage Comments</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Comment</th>
                        <th>Date</th>
                        <th>item_name</th>
                        <th>username</th>
                        <th>Control</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($rows as $row) {?>
                        <tr>
                            <td><?php echo $row['ID'] ?></td>
                            <td style="width: 300px"><?php echo $row['comment'] ?></td>
                            <td><?php echo $row['comment_date'] ?></td>
                            <td><?php echo $row['item_name'] ?></td>
                            <td> <?php echo $row['username'];?></td>
                            <td>
                                <a class="btn btn-warning" href="?action=edit&id=<?php echo $row['ID']?>"><i class="fa fa-edit"></i>  Edit</a>
                                <a class="btn btn-danger confirm" href="?action=delete&id=<?php echo $row['ID'] ?>"><i class="fa fa-close"></i> Delete</a>
                                <?php $approve =  ($row['status'] == 0) ? "<a href='?action=approve&id=$row[ID]' class='btn btn-info'><span class='glyphicon glyphicon-ok'></span> Approve</a>" : null; echo $approve;?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php } elseif ($save == "edit") {    // Start The Edit Page

        // Check If The Id Not Empty And Numeric
        $checkid = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;
        // select all data from user table
        $stmt = $con->prepare("SELECT * FROM comments WHERE ID = ? LIMIT 1");
        // Execute Query
        $stmt->execute(array($checkid));
        // Fetch The Data From Database To Use It In The Form Members
        $row = $stmt->fetch();
        // The Row Count
        $count = $stmt->rowCount();
        // If $count > 0 IF user id Found In Database And Show The Form After That
        if ($count > 0) { ?>
            <!-- HTML code -->

            <h1 class="text-center members-h1">Edit Comment</h1>
            <div class="container">
                <form class="form-horizontal members-form" action="?action=update" method="post">
                    <input type="hidden" value="<?php echo $row['ID'] ?>" name="id">
                    <div class="form-group">
                        <label for="comment" class="control-label col-sm-2 col-md-2 col-lg-2">Comment</label>
                        <div class="col-sm-8 col-md-10 col-lg-8">
                        <textarea class="form-control" name="comment" id="comment" required="required" placeholder="Edit Comment Here!" rows="4"><?php echo $row['comment'] ?></textarea>
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
    } elseif ($save == "update") {
        // Check If The Request Method is a Post Request
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Response The Data
        $id = $_POST['id'];
        $comment = $_POST['comment'];
        // Check IF The Field Empty Or Not
        if ($comment == '') {

            redirect('Sorry Comment Field Must Be Filled', 'danger', 'back', TRUE, TRUE, 2);

        } else {

            //  Update Comments Page

            // Update The Comment Data
            $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE ID = ?");
            $stmt->execute(array( $comment, $id));
            $count = $stmt->rowCount();
            // Check if Comment Updated Or Not
            if ($count > 0) {

                redirect($count . ' Record Updated', 'success', 'back', TRUE, TRUE, 2);
            } else {

                redirect($count . ' Record Updated', 'danger', 'back', TRUE, TRUE, 2);
            }
        }

        } else {

            redirect('ERROR 404', 'danger', '', TRUE, TRUE, 1);

        }
    } elseif ($save == "delete") {
        // Check If The Id Not Empty And Numeric
        $checkid = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;
        // Check If The Member ID Exists Or Not
        $check = check('ID', 'comments', $checkid);
        if ($check > 0) {

            $stmt = $con->prepare("DELETE FROM comments WHERE ID = :id");
            $stmt->bindParam('id' ,$checkid);
            $stmt->execute();
            $count = $stmt->rowCount();
            redirect( $count . " Record Deleted Successfully", 'success', 'back', TRUE, 4);
        } else {

            redirect( "There Is No Such ID", 'danger', 'back', TRUE, 3);
        }

    }   elseif ($save == "approve") {

        // Check If The Id Not Empty And Numeric
        $checkid = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;

        $check = check('ID', 'comments', $checkid);
        if ($check > 0) {

            $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE ID = ?");
            $stmt->execute(array($checkid));
            $count = $stmt->rowCount();
            if ($count > 0) {

                redirect( "The Comment Has Been Approved", 'success', 'back', TRUE, 3);

            }
        } else {

            redirect( "Comment Not Exists", 'danger', 'back', TRUE, 3);

        }

    } else { // If User Set A value For $save And Not Found

        redirect('ERROR 404', 'danger','',TRUE,3);

    }

    include $tmp . "footer.php";

} else {

    header('location: index.php');

    exit();
}
ob_end_flush();