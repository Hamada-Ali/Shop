<?php

/*
 ==============================================
 ================= Categories Page ============
 ==============================================
 */
ob_start(); // Prevent To Buffering The Header
session_start(); // Start The Session
$title = "Categories"; // Title Of The Page
include "init.php"; // Include All files In Init.php file
// Check If The User Entered To The Page By The Right Way
if (isset($_SESSION['username'])) {
   $action = (isset($_GET['action'])) ? $_GET['action'] : 'manage';

   if ($action == 'manage') {
        // Select All Data From Database
       $order = "ASC";
       $order_type = array("ASC", "DESC");
       if (isset($_GET['sort']) && in_array($_GET['sort'] , $order_type)) {
           $order = $_GET['sort'];
       }
       $stmt = $con->prepare("SELECT * FROM categories WHERE main_cat = 0 ORDER BY ordering $order");
       $stmt->execute();
       $fetch = $stmt->fetchAll();
       ?>
        <div class="container categories">
            <h1 class="text-center">Manage Categories</h1>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Manage Categories
                </div>
                <div class="panel-body">
                    <?php

                    foreach ($fetch as $data) {
                        echo "<div class='cat'>";
                        echo "<h3>" . $data['name'] . "<i class='fa fa-caret-down pull-right fa-3x caretme'></i>"  . "</h3>" . "<br>";
                        echo "<div class='full-view'>";
                        if ($data['description'] == '') {echo "<p class='desc'>No Information For This Category Here</p>";}
                        else {echo "<p class='desc'>" . $data['description'] . "</p>";}
                        if ($data['visibility'] == 1) {echo "<span class='visibility'><i class='fa fa-eye-slash'></i> Hidden</span>";}
                        if ($data['allow_comment'] == 1) {echo "<span class='commenting'><i class='fa fa-ban'></i> Comments Disabled</span>";}
                        if ($data['allow_ads'] == 1) {echo "<span class='advertise'><i class='fa fa-times-circle-o'></i> Ads Disabled</span>";}
                         echo "<a class='pull-right buttons' href='categories.php?action=subCats&id=$data[ID]'><i class='fa fa-list'></i> Sub</a>";
                        echo "<a class='pull-right buttons warnme' href='categories.php?action=delete&id=$data[ID]'><i class='fa fa-trash-o'></i> Delete</a>";
                        echo "<a class='pull-right buttons' href='categories.php?action=edit&id=$data[ID]'><i class='fa fa-pencil'></i> Edit</a>";
                        echo "</div>";
                        echo "</div>";
                    }
                    ?>

                    <a href='?sort=ASC' class="<?php if ($order == "ASC") {echo "active-link";} ?> pull-right orderstyle">ASC <i class="fa fa-long-arrow-down"></i></a>
                    <a href='?sort=DESC' class=" <?php if ($order == "DESC") {echo "active-link";} ?> pull-right orderstyle">DESC <i class="fa fa-long-arrow-up"></i></a>
            </div>
        </div>
        <a class="add-style" href="categories.php?action=add"><i class="fa fa-plus"></i> Add Category</a>

   <?php } elseif ($action == 'edit') {

       // Check If The Id Valid And Numeric

       $checkid = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;

       // Select All Data From Database

       $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");

       // Execute The Statement

       $stmt->execute(array($checkid));

       // Fetch All Category Data

       $fetch = $stmt->fetch();

       // Count If The ID Exist Or Not

       $count = $stmt->rowCount();

       if ($count > 0) { ?>

           <h1 class="text-center members-h1">Edit Category</h1>
           <div class="container">
               <form class="form-horizontal members-form" action="?action=update" method="post">
                   <input type="hidden" name="ID" value="<?php echo $fetch['ID'] ?>">
                   <!-- Category Section -->
                   <div class="form-group">
                       <label class="control-label col-sm-2 col-md-2 col-lg-2" for="category_name">Name</label>
                       <div class="col-sm-8 col-md-10 col-lg-8">
                           <input type="text" class="form-control" id="category_name" name="category"
                                  required="required" placeholder="Category Name" value="<?php echo $fetch['name'] ?>">
                       </div>
                   </div>
                   <!-- Category Description Section -->
                   <div class="form-group">
                       <label class="control-label col-sm-2 col-md-2 col-lg-2" for="category_description">Description</label>
                       <div class="col-sm-8 col-md-10 col-lg-8">
                           <input type="text" class="form-control placeholder" id="category_description" name="description"
                                  placeholder="Category Description" value="<?php echo $fetch['description'] ?>">
                       </div>
                   </div>
                   <!-- Category Ordering Section -->
                   <div class="form-group">
                       <label class="control-label col-sm-2 col-md-2 col-lg-2" for="category_ordering">Ordering</label>
                       <div class="col-sm-8 col-md-10 col-lg-8">
                           <input type="text" class="form-control" id="category_ordering" name="ordering"
                                  placeholder="Category Ordering" value="<?php echo $fetch['ordering'] ?>">
                       </div>
                   </div>
                                 <!-- Main Category Section -->
                   <div class="form-group">
                       <label class="control-label col-sm-2 col-md-2 col-lg-2" for="main_cat">Main Category</label>
                       <div class="col-sm-8 col-md-10 col-lg-8">
                           <select name="main_cat" id="main_cat">
                           <option value="0">NONE</option>
                           <?php
                           $mainCats = get_all_from("*", "categories", "WHERE main_cat = 0", "ID");
                           foreach ($mainCats as $cat) {

                               echo "<option value='$cat[ID]'>$cat[name]</option>";

                           }
                           ?>
                           </select>
                       </div>
                   </div>
                    <!-- End Main Category Section -->
                   <div class="form-group">
                       <label class="control-label col-sm-2 col-md-2 col-lg-2">Visible</label>
                       <div class="col-sm-8 col-md-10 col-lg-8">
                           <div class="style-label">
                               <label for="vis-yes" class="control-label">Yes</label>
                               <input type="radio" id="vis-yes"  name="visibility" value="0" <?php if($fetch['visibility'] == 0){echo "checked";}?>>
                           </div>
                           <div class="style-label">
                               <label for="vis-no" class="control-label">No</label>
                               <input type="radio" id="vis-no" value="1" <?php if($fetch['visibility'] == 1){echo "checked";}?> name="visibility">
                           </div>
                       </div>
                   </div>
                   <div class="form-group">
                       <label class="control-label col-sm-2 col-md-2 col-lg-2">Allow Comments</label>
                       <div class="col-sm-8 col-md-10 col-lg-8">
                           <div class="style-label">
                               <label for="com-yes" class="control-label">Yes</label>
                               <input type="radio" id="com-yes"  value="0" name="comments" <?php if($fetch['allow_comment'] == 0){echo "checked";}?>>
                           </div>
                           <div class="style-label">
                               <label for="com-no" class="control-label">No</label>
                               <input type="radio" id="com-no" value="1" name="comments" <?php if($fetch['allow_comment'] == 1){echo "checked";}?>>
                           </div>
                       </div>
                   </div>
                   <div class="form-group">
                       <label class="control-label col-sm-2 col-md-2 col-lg-2">Allow Ads</label>
                       <div class="col-sm-8 col-md-10 col-lg-8">
                           <div class="style-label">
                               <label for="ads-yes">Yes</label>
                               <input type="radio" id="ads-yes"  value="0" name="ads" <?php if($fetch['allow_ads'] == 0){echo "checked";}?>>
                           </div>
                           <div class="style-label">
                               <label for="ads-no" class="control-label">No</label>
                               <input type="radio" id="ads-no" value="1" name="ads" <?php if($fetch['allow_ads'] == 1){echo "checked";}?>>
                           </div>
                       </div>
                   </div>
                   <!-- Button -->
                   <div class="form-group">
                       <div class="col-sm-12">
                           <input type="submit" class="btn btn-primary center-block" value="Edit">
                       </div>
                   </div>

               </form>
           </div>
       <?php }

} elseif ($action == 'update') {

// Check If The Request Method Is Post Request

       if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Variables
           $id = $_POST['ID'];
           $name = $_POST['category'];
           $description = $_POST['description'];
           $ordering = $_POST['ordering'];
           $visibility = $_POST['visibility'];
           $comments = $_POST['comments'];
           $ads = $_POST['ads'];
           $main = $_POST['main_cat'];

            // Statement
           $stmt = $con->prepare("UPDATE
                                                categories 
                                            SET 
                                                name = ?, description = ?, ordering = ?, visibility = ?, allow_comment = ?, allow_ads = ?, main_cat = ?
                                            WHERE 
                                                ID = ?
                                       
                                                ");
           $stmt->execute(array($name, $description, $ordering, $visibility, $comments, $ads, $main, $id));
           $count = $stmt->rowCount();
           if ($count > 0 ) {
               redirect($count . "Record Updated", "success", "back", TRUE, 5);

           } else {

               redirect($count . "Record Updated", "danger", "back", TRUE, 3);

           }

       } else {

               redirect("ERROR 404", "danger", "back", TRUE, 2);

       }


} elseif ($action == 'add') {?>
<!-- Start Add Categories Page-->

       <h1 class="text-center members-h1">Add Category</h1>
       <div class="container">
           <form class="form-horizontal members-form" action="?action=insert" method="post">
               <!-- Category Section -->
               <div class="form-group">
                   <label class="control-label col-sm-2 col-md-2 col-lg-2" for="category_name">Name</label>
                   <div class="col-sm-8 col-md-10 col-lg-8">
                       <input type="text" class="form-control" id="category_name" name="category"
                              required="required" placeholder="Category Name">
                   </div>
               </div>
               <!-- Category Description Section -->
               <div class="form-group">
                   <label class="control-label col-sm-2 col-md-2 col-lg-2" for="category_description">Description</label>
                   <div class="col-sm-8 col-md-10 col-lg-8">
                       <input type="text" class="form-control placeholder" id="category_description" name="description"
                               placeholder="Category Description">
                   </div>
               </div>
               <!-- Category Ordering Section -->
               <div class="form-group">
                   <label class="control-label col-sm-2 col-md-2 col-lg-2" for="category_ordering">Ordering</label>
                   <div class="col-sm-8 col-md-10 col-lg-8">
                       <input type="text" class="form-control" id="category_ordering" name="ordering"
                         placeholder="Category Ordering">
                   </div>
               </div>
                                <!-- Main Category Section -->
                   <div class="form-group">
                       <label class="control-label col-sm-2 col-md-2 col-lg-2" for="main_cat">Main Category</label>
                       <div class="col-sm-8 col-md-10 col-lg-8">
                           <select name="main_cat" id="main_cat">
                           <option value="0">NONE</option>
                           <?php
                           $mainCats = get_all_from("*", "categories", "WHERE main_cat = 0", "ID");
                           foreach ($mainCats as $cat) {

                               echo "<option value='$cat[ID]'>$cat[name]</option>";

                           }
                           ?>
                           </select>
                       </div>
                   </div>
               <div class="form-group">
                   <label class="control-label col-sm-2 col-md-2 col-lg-2">Visible</label>
                   <div class="col-sm-8 col-md-10 col-lg-8">
                       <div class="style-label">
                           <label for="vis-yes" class="control-label">Yes</label>
                           <input type="radio" id="vis-yes" checked value="0" name="visibility">
                           </div>
                       <div class="style-label">
                       <label for="vis-no" class="control-label">No</label>
                           <input type="radio" id="vis-no" value="1" name="visibility">
                       </div>
                   </div>
               </div>
               <div class="form-group">
                   <label class="control-label col-sm-2 col-md-2 col-lg-2">Allow Comments</label>
                   <div class="col-sm-8 col-md-10 col-lg-8">
                       <div class="style-label">
                           <label for="com-yes" class="control-label">Yes</label>
                           <input type="radio" id="com-yes" checked value="0" name="comments">
                       </div>
                       <div class="style-label">
                           <label for="com-no" class="control-label">No</label>
                           <input type="radio" id="com-no" value="1" name="comments">
                       </div>
                   </div>
               </div>
               <div class="form-group">
                   <label class="control-label col-sm-2 col-md-2 col-lg-2">Allow Ads</label>
                   <div class="col-sm-8 col-md-10 col-lg-8">
                       <div class="style-label">
                           <label for="ads-yes">Yes</label>
                           <input type="radio" id="ads-yes" checked value="0" name="ads">
                       </div>
                       <div class="style-label">
                           <label for="ads-no" class="control-label">No</label>
                           <input type="radio" id="ads-no" value="1" name="ads">
                       </div>
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

<!-- End Add Categories Page-->
<?php } elseif ($action == 'insert') {

       // Check If The Request Method iS a Post Request
       if ($_SERVER['REQUEST_METHOD'] == 'POST') {
           echo "<div class='container text-center'>";
           echo "<h1>Insert Category</h1>";
           echo "</div>";

           // Data Reception From Category Form
           $name        = $_POST['category'];
           $description = $_POST['description'];
           $ordering    = $_POST['ordering'];
           $visibility  = $_POST['visibility'];
           $comments    = $_POST['comments'];
           $ads         = $_POST['ads'];
           $allcats     = $_POST['main_cat'];

           // Check If The Username Booked Up Or Not
           if (check('name', 'categories', $name) == 0) {

           // Add Member To Database
           $stmt = $con->prepare("INSERT
                                            INTO 
                                            categories(name, description, ordering, main_cat ,visibility, allow_comment , allow_ads) 
                                            VALUES 
                                            (:name, :desc, :order, :main ,:vis, :com , :ads)
                                            ");
           $stmt->execute(array(
               ":name"  => $name,
               ":desc"  => $description,
               ":order" => $ordering,
               ":main"  => $allcats,
               ":vis"   => $visibility,
               ":com"   => $comments,
               ":ads"   => $ads

           ));
           // Return A massage If EveryThing Is Good And The data saved In Database
           $count = $stmt->rowCount();

           // Alert A Massage if The Data Saved In Database
           redirect( $count . " Record Added Successfully", 'success', 'back', TRUE, 3);

           } else {

            // Return A Massage if The Name Of Category Is Alredy Exists
               redirect( 'Sorry The Username Booked Up', 'danger', 'back', TRUE, 3);

               } // end inserting data

           } else { // if the user entered to the page directly

           redirect( 'ERROR 404', 'danger', 'back', TRUE, 2);

       }

    } elseif ($action == 'delete') {

       $myid =  (isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0);

        $check = check('ID', 'categories', $myid);

        if ($check > 0) {

            $stmt = $con->prepare("DELETE FROM categories WHERE ID = :id");
            $stmt->bindParam(":id", $myid);
            $stmt->execute();
            $count = $stmt->rowCount();
            if ($count > 0) {

                redirect($count . " Record Updated", 'success', 'back', TRUE, 2);

            } else {

                redirect($count . " Record Updated", 'danger', 'back', TRUE, 2);

            }

        } else {

            redirect("Value Not Exists", 'danger', 'back', TRUE, 2);

        }

   } elseif ($action == "subCats") {
       $id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;
       $mainCats = get_all_from("*", "categories", "WHERE ID = $id ", "ID", "ASC");
     ?>

       <div class="container">
       <h1 class="text-center">Sub Categories</h1>

            <?php

            foreach ($mainCats as $cat) {

                 $subCats = get_all_from("*", "categories", "WHERE main_cat = $cat[ID]", "ID");
                    if (! empty($subCats)) {
                 foreach ($subCats as $sub) {
                     echo "<div class='sub-cats''>";
                     echo  "<h2>- <a href='categories.php?action=edit&id=$sub[ID]' class='sub'>" . $sub['name'] . "</a></h2>";
                     echo "<a class='btn btn-success pull-right' href='categories.php?action=edit&id=$sub[ID]'><i class='fa fa-pencil'></i> Edit</a>";
                     echo "<a class='btn btn-danger pull-right warnme' href='categories.php?action=delete&id=$sub[ID]'><i class='fa fa-trash'></i> Delete</a>";
                     echo " </div>";
                 }

                } else {

                        echo "<div class='alert alert-danger text-center'>There Is No Any Category</div>";

                }
            }
            ?>

        </div>
    <?php
   } else {

       redirect('ERROR 404', 'danger', 'back', TRUE, '2'); // Redirect The User If He Entered To Page Not Found.
   }
} else {

    header("location:index.php"); // If The User Entered To The Pag Directly

}
include $tmp . "footer.php"; // include footer
ob_end_flush(); // end the output buffering