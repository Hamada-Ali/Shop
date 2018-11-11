<?php
ob_start();
session_start();
if (isset($_SESSION['user'])) {
    $title = "New Ad";
    include "init.php";
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
        $status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);

        $formErrors = array();

        if (strlen($name) < 4) {

            $formErrors[] = "Sorry The Item Name Must Be Larger Than 4 Characters";

        }

        if (empty($name)) {

            $formErrors[] = "The Item Field Must Be Filled";

        }

        if (strlen($description) < 8) {

            $formErrors[] = "The Description Must Contain 8 Characters At Least";

        }

        if (empty($price)) {

            $formErrors[] = "You Must Set A Price For This Item";

        } elseif (filter_var($_POST['price'], FILTER_VALIDATE_INT) === FALSE) {

            $formErrors[] = "Price Field Accept Numbers Only";

        }

        if (strlen($country) <= 1) {

            $formErrors[] = "Please Enter A Valid Country";

        }

        if (empty($status)) {

            $formErrors[] = "Enter The Item Status";

        }

        if (empty($category)) {

            $formErrors[] = "Please Choose A Category For This Item";

        }

        if (empty($formErrors)) {

            $stmt2 = $con->prepare("INSERT 
                                             INTO 
                                                   items(name, description, price, country, status, cat_id, member_id, date)
                                             VALUES
                                                   (:name, :description, :price, :country, :status, :cat_id, :member_id, NOW())
                                             ");
            $stmt2->execute(array(

                ":name"        => $name,
                ":description" => $description,
                ":price"       => $price,
                ":country"     => $country,
                ":status"      => $status,
                ":cat_id"      => $category,
                ":member_id"   => $_SESSION['userid']
            ));

            $count = $stmt2->rowCount();

            if ($count > 0) {

                $msg = "Add " . $name . " Item Successfully";

            } else {

                $formErrors[] = "Sorry Something Error Please Try Again Later";

            }

        }

    }
    ?>

    <h1 class="text-center">New Ad</h1>
    <div class="container ads-container">
        <div class="panel panel-primary">
            <div class=" panel-heading">
                Add New Ad
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="form-horizontal ad-form">
                            <div class="form-group">
                                <label class="control-label col-md-2" for="name">Item Name:</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" required="required" id="name" name="name"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="description">Description:</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" required="required" id="description"
                                           name="description"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="price">Price:</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" required="required" id="price"
                                           name="price"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="country">Country:</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" required="required" id="country"
                                           name="country"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="status">Status:</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="status" id="status">
                                        <option value="">...</option>
                                        <option value="1">New</option>
                                        <option value="2">Recycled</option>
                                        <option value="3">Used</option>
                                        <option value="4">Old</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="Category">Category:</label>
                                <div class="col-md-8">
                                    <select class="form-control" id="Category" name="category">
                                        <?php
                                        $stmt = $con->prepare("SELECT * FROM categories");
                                        $stmt->execute();
                                        $cats = $stmt->fetchAll();
                                        echo "<option value=''>...</option>";
                                        foreach ($cats as $cat) {
                                            echo "<option value='$cat[ID]'>" . $cat['name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 text-center">
                                <input value="Add Item" type="submit" class="btn btn-primary">
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail item-box ad-thumbnail">
                            <span class="price">0</span>
                            <img src="img2.png" alt="image here" class="img-responsive">
                            <div class="caption">
                                <h3>Name</h3>
                                <p>Description</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <?php
                    if (isset($msg)) {

                        echo "<div class='alert alert-success err-alert'>" . $msg . "</div>";

                    }
                    if (!empty($formErrors)) {

                        foreach ($formErrors as $error) {

                            echo "<div class='alert alert-danger err-alert'>" . $error . "</div>";

                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    include $tmp . "footer.php";
} else {

    header("location: login.php");
    exit();

}
ob_end_flush();
