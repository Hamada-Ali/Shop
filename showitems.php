<?php
ob_start();
session_start();
$title = "View Item";
include "init.php";
$checkId = (isset($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : 0;
$stmt = $con->prepare("SELECT 
                                        items.*, categories.name AS category, users.username
                                FROM 
                                        items
                             
                                INNER JOIN
                                        categories
                                ON
                                        items.cat_id = categories.ID
                                INNER JOIN
                                        users
                                ON
                                        items.member_id = users.UserID
                                WHERE 
                                        items.ID = ?
                                AND
                                        approve = 1");
$stmt->execute(array($checkId));
$fetch = $stmt->fetch();
$count = $stmt->rowCount();
if ($count > 0) {
    echo "<h1 class='text-center'>" . $fetch['name'] . "</h1>";
    ?>
    <div class="container show-items">
        <div class="row">
            <div class="col-md-3">
                <img alt="image here" src="img2.png" class="img-thumbnail text-center"/>
            </div>
            <div class="col-md-9">
                <div class="show-items-style">
                    <h2><?php echo $fetch['name'] ?></h2>
                    <p><?php echo $fetch['description'] ?></p>
                    <ul class="show-item-list">
                        <li><span><i class="fa fa-money"></i> Price</span><?php echo ": $" . $fetch['price'] ?></li>
                        <li><span><i class="fa fa-calendar"></i> Date</span><?php echo ": " . $fetch['date'] ?></li>
                        <li><span><i class="fa fa-globe"></i> Country Made</span><?php echo ": " . $fetch['country'] ?>
                        </li>
                        <li><span><i class="fa fa-tags"></i> Category</span>: <a
                                    href="categories.php?name=<?php echo $fetch['category'] ?>&id=<?php echo $fetch['cat_id'] ?>"><?php echo $fetch['category'] ?></a>
                        </li>
                        <li><span><i class="fa fa-user-circle"></i> Added By</span>: <a
                                    href="#"><?php echo $fetch['username'] ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <hr class="hr-style"/>
        <?php if (isset($_SESSION['user'])) { ?>
            <div class="container">
                <div class="col-md-offset-3">
                    <form action="<?php echo $_SERVER['PHP_SELF'] . "?id=$fetch[ID]" ?>" method="post">
                        <h2>Add Your Comment Here:</h2>
                        <textarea name="comment" id="" cols="50" rows="10" required="required"></textarea>
                        <input type="submit" class="btn btn-lg btn-default" value="Add Comment">
                    </form>
                    <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                        $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                        $userid = $_SESSION['userid'];
                        $itemid = $fetch['ID'];

                        if (!empty($comment)) {
                            $stmt = $con->prepare("INSERT
                                                    INTO
                                                        comments(comment, status, comment_date, user_id, item_id)
                                                    VALUES
                                                        (:comment, 0, NOW(), :user_id, :item_id)
                                                        ");
                            $stmt->execute(array(
                                ":comment" => $comment,
                                ":user_id" => $userid,
                                ":item_id" => $itemid
                            ));

                            $count = $stmt->rowCount();
                            if ($count > 0) {

                                echo "<div class='alert alert-success'>Comment Added</div>";

                            }

                        } else {
                            echo "<div class='alert alert-danger'>";
                            echo "You Must Add Comment";
                            echo "</div>";
                        }
                    } ?>
                </div>
            </div>
        <?php } else {

            echo " Please " . "<a href='login.php'>Login</a> To Add a Comment";

        } ?>
    </div>
    <hr class="hr-style"/>
    <?php
    $stmt2 = $con->prepare("SELECT comments.*, users.username
                                                FROM comments 
                                                INNER JOIN users
                                                ON
                                                comments.user_id = users.UserID
                                                 WHERE item_id = ? 
                                                 AND
                                                 status = 1
                                                 ORDER BY ID DESC
                      
                                                ");
    $stmt2->execute(array($fetch['ID']));
    $comments = $stmt2->fetchAll();
    $count = $stmt2->rowCount();
    if ($count > 0) {
        foreach ($comments as $comment) {
            echo "<div class='comments-area'>";
            echo "<div class='row'>";
            echo "<div class='col-sm-2 col-sm-offset-1'>";
            echo "<img alt='image' src='img2.png' class='img-thumbnail img-circle center-block'/>";
            echo "<span class='text-center' style='display: block'>" . $comment['username'] . "</span>" . "<br>";
            echo "</div>";
            echo "<div class='col-sm-8 '>";
            echo "<p class='lead'>" . $comment['comment'] . "</p>" . "<br>";
            echo "</div>";
            echo "</div>";
            echo "<div>";

        }
    }

} else {

    redirect("Sorry There Is no Such ID or The Item Not Approved", 'danger', 'back', TRUE, TRUE, 3);

}
?>
<?php
include $tmp . "footer.php";
ob_end_flush();