<?php
ob_start();
session_start();
// Title For The Page
$title = "Items";
if (isset($_SESSION['username'])) {
    $action = (isset($_GET['action']) ? $_GET['action'] : "manage");
include "init.php";

if ($action == "manage") {?>

    <?php
        // Fetch All Data From Items Table And Join Table Items With Tables Categories And Users
    $stmt = $con->prepare("SELECT 
                                            items.*, 
                                            categories.name AS category_name, 
                                            users.username 
                                    FROM
                                            items
                                    INNER 
                                    JOIN 
                                            categories                                        
                                    ON 
                                            items.cat_id = categories.ID
                                    INNER 
                                    JOIN
                                            users
                                    ON
                                            items.member_id = users.UserID
                                                ");
    $stmt->execute();
    $rows = $stmt->fetchAll();
    ?>
    <!-- Start Manage Page -->
        <h1 class="text-center" style="margin: 25px 0">Manage Items</h1>
        <div class="container-fluid">
            <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead>
                <tr>
                    <th>#ID</th>
                    <th>name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Date</th>
                    <th>Country</th>
                    <th>Category Name</th>
                    <th>Username</th>
                    <th>Control</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($rows as $row) {?>
                    <tr>
                        <td><?php echo $row['ID'] ?></td>
                        <td><?php echo $row['name'] ?></td>
                        <td><?php echo $row['description'] ?></td>
                        <td><?php echo "$" . $row['price'] ?></td>
                        <td> <?php echo $row['date'];?></td>
                        <td> <?php echo $row['country'];?></td>
                        <td> <?php echo $row['category_name'];?></td>
                        <td> <?php echo $row['username'];?></td>
                        <td>
                            <a class="btn btn-warning" href="?action=edit&id=<?php echo $row['ID']?>"><i class="fa fa-edit"></i>  Edit</a>
                            <a class="btn btn-danger confirm" href="?action=delete&id=<?php echo $row['ID'] ?>"><i class="fa fa-close"></i> Delete</a>
                            <?php $approve =  ($row['approve'] == 0) ? "<a href='?action=approve&id=$row[ID]' class='btn btn-info'><span class='glyphicon glyphicon-ok'></span> Approve</a>" : null;echo $approve;?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            </div>
            <a href='?action=add' class='btn btn-success'><i class='fa fa-plus'></i>  Add Item</a>
        </div>
    <!-- Start Manage Page -->
<?php } elseif ($action == "add") { ?>
<!-- Start Add Items Page -->
    <h1 class="text-center members-h1">Add Item</h1>
       <div class="container">
           <form class="form-horizontal members-form" action="?action=insert" method="post">
               <!-- Start Items Names Section -->
               <div class="form-group">
                   <label class="control-label col-sm-2 col-md-2 col-lg-2" for="item_name">Name</label>
                   <div class="col-sm-8 col-md-10 col-lg-8">
                       <input type="text" class="form-control" id="item_name" name="item"
                               placeholder="Item Name" required="required">
                   </div>
               </div>
               <!-- Start Items Names Section -->
               <!-- Start Items Description Section -->
               <div class="form-group">
                   <label class="control-label col-sm-2 col-md-2 col-lg-2" for="item_description">Description</label>
                   <div class="col-sm-8 col-md-10 col-lg-8">
                       <input type="text" class="form-control placeholder" id="item_description" name="description"
                               placeholder="Item Description"  required="required">
                   </div>
               </div>
               <!-- End Items Description Section -->
               <!-- Start Items prices Section -->
               <div class="form-group">
                   <label class="control-label col-sm-2 col-md-2 col-lg-2" for="item_price">Price</label>
                   <div class="col-sm-8 col-md-10 col-lg-8">
                       <input type="text" class="form-control" id="item_price" name="price"
                         placeholder="Item Price"  required="required">
                   </div>
               </div>
               <!-- End Items Prices-->
               <!--Start Country Made -->
               <div class="form-group">
                   <label class="control-label col-sm-2 col-md-2 col-lg-2" for="item_country">Country</label>
                   <div class="col-sm-8 col-md-10 col-lg-8">
                       <input type="text" class="form-control" id="item_country" name="country"
                              placeholder="Item Country Made"  required="required">
                   </div>
               </div>
               <!--End Country Made-->
               <!--Start Status Item -->
               <div class="form-group">
                   <label class="control-label col-sm-2 col-md-2 col-lg-2" for="item_status">Status</label>
                   <div class="col-sm-8 col-md-10 col-lg-8">
                       <select name="status" id="item_status">
                           <option value="0">...</option>
                           <option value="1">New</option>
                           <option value="2">Recycled</option>
                           <option value="3">Used</option>
                           <option value="4">Old</option>
                       </select>
                   </div>
               </div>

               <!--End Status Item-->
               <!--Start Member Name -->
               <div class="form-group">
                   <label class="control-label col-sm-2 col-md-2 col-lg-2" for="member">Member</label>
                   <div class="col-sm-8 col-md-10 col-lg-8">
                       <select name="member" id="member">
                           <option value="0">...</option>
                           <?php
                            // Select All Active Members
                           $stmt = $con->prepare("SELECT * FROM users");
                           $stmt->execute();
                           $full_data = $stmt->fetchAll();
                           foreach($full_data as $data) {

                               echo "<option value='$data[UserID]'>$data[username]</option>";
                           }
                           ?>
                       </select>
                   </div>
               </div>
               <!--End Member Name -->
               <!--Start Category Name -->
               <div class="form-group">
                   <label class="control-label col-sm-2 col-md-2 col-lg-2" for="category">Category</label>
                   <div class="col-sm-8 col-md-10 col-lg-8">
                       <select name="category" id="category">
                           <option value="0">...</option>
                           <?php
                           // Select All Categories
                           $full_data = get_all_from("*", "categories", " WHERE main_cat = 0", "ID", "ASC");

                           foreach($full_data as $data) {

                               echo "<option value='$data[ID]'>" . $data['name'] . "</option>";
                               $sub_cat = get_all_from("*", "categories", " WHERE main_cat = $data[ID]", "ID", "ASC");
                               foreach ($sub_cat as $cat) {

                                   echo "<option value='$cat[ID]'>" . "--- " . $cat['name'] . " ---" . "</option>";

                               }

                           }
                           ?>
                       </select>
                   </div>
               </div>
               <!--Start Tags-->
               <div class="form-group">
                   <label class="control-label col-sm-2 col-md-2 col-lg-2" for="tags">Tags</label>
                   <div class="col-sm-8 col-md-10 col-lg-8">
                       <input type="text" class="form-control" id="tags" name="tags">
                   </div>
               </div>
               <!--End Tags-->
               <!-- Button -->
               <div class="form-group">
                   <div class="col-sm-12">
                       <input type="submit" class="btn btn-primary center-block" value="Add Item">
                   </div>
               </div>
           </form>
       </div>
    <!-- End Add Items Page -->
<?php } elseif ($action == "insert"){
     // Insert Items Page

    // Check If The Request Method POST or not
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Set Variables For Form Values
        $name        = $_POST['item'];
        $description = $_POST['description'];
        $price       = $_POST['price'];
        $country     = $_POST['country'];
        $status      = $_POST['status'];
        $member      = $_POST['member'];
        $category    = $_POST['category'];
        $tags        = $_POST['category'];

        // Check If Form Field Empty Or Not
        $formErrors = array();
        if (empty($name)) {


            $formErrors[] = "Name Filed Must Be Filled";

        }

        if (empty($description)) {


            $formErrors[] = "Description Field Must Be Filled";

        }

        if (empty($price)) {


            $formErrors[] = "Price Field Must Be Filled";

        }

        if (empty($country)) {


            $formErrors[] = "country Field Must Be Filled";

        }

        if ($status == 0) {


            $formErrors[] = "You Must Select Type Of Item Status";

        }

        if ($member == 0) {


            $formErrors[] = "You Must Select Member Name";

        }

        if ($category == 0) {


            $formErrors[] = "You Must Select Category Name";

        }

        foreach($formErrors as $error) {

            redirect($error, 'danger', 'back', TRUE, FALSE, '2');
        }

        // If No Errors Inset The Data To Database
        if (empty($formErrors)) {

            $stmt = $con->prepare("INSERT 
                                            INTO 
                                            items(name, description, price, country, status, date, cat_id, member_id, tags)
                                            VALUES
                                            (:name, :description, :price, :country, :status, NOW(), :cat, :member, :tags)
                                            ");
            $stmt->execute(array(
                ":name"        => $name,
                ":description" => $description,
                ":price"       => $price,
                ":country"     => $country,
                ":status"      => $status,
                ":cat"         => $category,
                ":member"      => $member,
                ":tags"        => $tags
            ));

            // Alert A Success Massage If Records Added
            $count = $stmt->rowCount();

            if ($count > 0) {

                redirect($count . " Records Added", 'success', 'back', TRUE, TRUE, 3);

            } else {

                redirect($count . "Records Added", 'danger', 'back', TRUE, TRUE, 3);

            }

        }

    } else {

        // if The User Entered To The Page Directly Show An Error Massage
        redirect("ERROR 404", "danger", "", TRUE, "2");

    }
} elseif ($action == "edit"){

    // Start Check Id In The Link Exist Or Not
    $itemid = (isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0);
    $stmt = $con->prepare("SELECT * FROM items WHERE ID = ?");
    $stmt->execute(array($itemid));
    $count = $stmt->rowCount();

    if ($count > 0) {
        $fetch =$stmt->fetch();
        ?>
        <!-- Start Add Items Page -->
    <h1 class="text-center members-h1">Edit Item</h1>
       <div class="container">
           <form class="form-horizontal members-form" action="?action=update" method="post">
               <input type="hidden" name="id" value="<?php echo $itemid; ?>">
               <!-- Start Items Names Section -->
               <div class="form-group">
                   <label class="control-label col-sm-2 col-md-2 col-lg-2" for="item_name">Name</label>
                   <div class="col-sm-8 col-md-10 col-lg-8">
                       <input type="text" class="form-control" id="item_name" name="item"
                               placeholder="Item Name" required="required" value="<?php echo $fetch['name'] ?>">
                   </div>
               </div>
               <!-- Start Items Names Section -->
               <!-- Start Items Description Section -->
               <div class="form-group">
                   <label class="control-label col-sm-2 col-md-2 col-lg-2" for="item_description">Description</label>
                   <div class="col-sm-8 col-md-10 col-lg-8">
                       <input type="text" class="form-control placeholder" id="item_description" name="description"
                               placeholder="Item Description"  required="required" value="<?php echo $fetch['description'] ?>">
                   </div>
               </div>
               <!-- End Items Description Section -->
               <!-- Start Items prices Section -->
               <div class="form-group">
                   <label class="control-label col-sm-2 col-md-2 col-lg-2" for="item_price">Price</label>
                   <div class="col-sm-8 col-md-10 col-lg-8">
                       <input type="text" class="form-control" id="item_price" name="price"
                         placeholder="Item Price"  required="required" value="<?php echo $fetch['price'] ?>">
                   </div>
               </div>
               <!-- End Items Prices-->
               <!--Start Country Made -->
               <div class="form-group">
                   <label class="control-label col-sm-2 col-md-2 col-lg-2" for="item_country">Country</label>
                   <div class="col-sm-8 col-md-10 col-lg-8">
                       <input type="text" class="form-control" id="item_country" name="country"
                              placeholder="Item Country Made"  required="required" value="<?php echo $fetch['country'] ?>">
                   </div>
               </div>
               <!--End Country Made-->
               <!--Start Status Item -->
               <div class="form-group">
                   <label class="control-label col-sm-2 col-md-2 col-lg-2" for="item_status">Status</label>
                   <div class="col-sm-8 col-md-10 col-lg-8">
                       <select name="status" id="item_status">
                           <option value="1" <?php if ($fetch['status'] == 1) {echo "selected";} ?>>New</option>
                           <option value="2" <?php if ($fetch['status'] == 2) {echo "selected";} ?>>Recycled</option>
                           <option value="3" <?php if ($fetch['status'] == 3) {echo "selected";} ?>>Used</option>
                           <option value="4" <?php if ($fetch['status'] == 4) {echo "selected";} ?>>Old</option>
                       </select>
                   </div>
               </div>

               <!--End Status Item-->
               <!--Start Member Name -->
               <div class="form-group">
                   <label class="control-label col-sm-2 col-md-2 col-lg-2" for="member">Member</label>
                   <div class="col-sm-8 col-md-10 col-lg-8">
                       <select name="member" id="member">
                           <?php
                            // Select All Active Members
                           $stmt = $con->prepare("SELECT * FROM users");
                           $stmt->execute();
                           $full_data = $stmt->fetchAll();
                           foreach($full_data as $data) {

                               echo "<option value='$data[UserID]'"; if($data['UserID'] == $fetch['member_id']){echo "selected";} echo">$data[username]</option>";
                           }
                           ?>
                       </select>
                   </div>
               </div>
               <!--End Member Name -->
               <!--Start Category Name -->
               <div class="form-group">
                   <label class="control-label col-sm-2 col-md-2 col-lg-2" for="category">Category</label>
                   <div class="col-sm-8 col-md-10 col-lg-8">
                       <select name="category" id="category">
                           <?php
                           // Select All Categories
                           $stmt = $con->prepare("SELECT * FROM categories");
                           $stmt->execute();
                           $full_data = $stmt->fetchAll();
                           foreach($full_data as $data) {

                               echo "<option value='$data[ID]'";  if ($data['ID'] == $fetch['cat_id']) {echo "selected";} echo">$data[name]</option>";

                           }
                           ?>
                       </select>
                   </div>
               </div>
               <!--End Category Name -->
               <!-- Button -->
               <div class="form-group">
                   <div class="col-sm-12">
                       <input type="submit" class="btn btn-primary center-block" value="Edit Item">
                   </div>
               </div>
           </form>

<?php

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
                                                    WHERE item_id = ? 
           ");
           $stmt->execute(array($itemid));
           $rows = $stmt->fetchAll();
           if (! empty($rows)) {
           ?>

           <!-- Manage Page -->
           <hr style="border-color: #ded7d7;margin-top:50px;margin-bottom: 50px">
           <h1 class="text-center" style="margin: 25px 0">Manage [ <?php echo $fetch['name'] ?> ] Comments</h1>
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
                               <td><?php echo $row['comment'] ?></td>
                               <td><?php echo $row['comment_date'] ?></td>
                               <td><?php echo $row['item_name'] ?></td>
                               <td> <?php echo $row['username'];?></td>
                               <td>
                                   <a class="btn btn-warning" href="comments.php?action=edit&id=<?php echo $row['ID']?>"><i class="fa fa-edit"></i>  Edit</a>
                                   <a class="btn btn-danger confirm" href="comments.php?action=delete&id=<?php echo $row['ID'] ?>"><i class="fa fa-close"></i> Delete</a>
                                   <?php $approve =  ($row['status'] == 0) ? "<a href='comments.php?action=approve&id=$row[ID]' class='btn btn-info'><span class='glyphicon glyphicon-ok'></span> Approve</a>" : null; echo $approve;?>
                               </td>
                           </tr>
                       <?php } ?>
                       </tbody>
                       <?php } ?>
                   </table>
               </div>
       </div> <!--End Of Container-->
    <!-- End Edit Items Page -->

   <?php } else {

        redirect("Sorry There Is No Such ID", "danger", "", TRUE, TRUE, 1);

    }

} elseif ($action == "update") {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $id = $_POST['id'];
        $name = $_POST['item'];
        $desc = $_POST['description'];
        $price = $_POST['price'];
        $country = $_POST['country'];
        $status = $_POST['status'];
        $member = $_POST['member'];
        $category = $_POST['category'];


        $stmt = $con->prepare("UPDATE 
                                            items 
                                    SET 
                                            name = ?, 
                                            description = ?, 
                                            price = ?,
                                            country = ?,
                                            status = ?,
                                            cat_id = ?,
                                            member_id = ?
                                    WHERE 
                                            ID = ?
                                            ");

        $stmt->execute(array($name, $desc, $price, $country, $status, $category, $member , $id));
        $count_update = $stmt->rowCount();

        if ($count_update > 0) {

            redirect($count_update . " Record Updated", "success", "back", TRUE, TRUE, 2);

        } else {

            redirect($count_update . " Record Updated", "danger", "back", TRUE, TRUE, 2);

        }

    } else {

        redirect("ERROR 404", "danger", "", TRUE, TRUE, 1);

    }

} elseif ($action == "delete") {

    $checkid = (isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0);

    $stmt = $con->prepare("DELETE FROM items WHERE ID = ?");
    $stmt->execute(array($checkid));
    $count = $stmt->rowCount();
    if ($count > 0) {

        redirect( $count . " Record Deleted Successfully", "success", "BACK", TRUE, TRUE, 2);
    } else {

        redirect( $count . "Record Deleted", "danger", "BACK", TRUE, TRUE, 2);

    }

} elseif ($action == "approve"){

    // Approve Page

    $checkid = (isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0);

    $stmt = $con->prepare("UPDATE items SET approve = 1 WHERE ID = ?");
    $stmt->execute(array($checkid));
    $count = $stmt->rowCount();

    if ($count > 0 ) {

        redirect( $count . " Record Approved Successfully", "success", 'back', TRUE, TRUE, 2);

    } else {

        redirect( $count . " Record Approved", "danger", 'back', TRUE, TRUE, 2);

    }

} else {

    redirect("ERROR 404", 'danger', 'back', TRUE, 1);

}


include $tmp . "footer.php";
}

ob_end_flush();