<!DOCTYPE HTML>
<html>
<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>


<body>
<?php include 'menu.php';
    ?>
    <!-- container -->
    <?php
    // include database connection
    include 'config/database.php';

    // delete message prompt will be here

    // select all data
    $query = "SELECT * FROM product_cat";
    $stmt = $con->prepare($query);
    $stmt->execute();
    ?>
    <?php
    if ($_POST) {
        // include database connection
        include 'config/database.php';
        try {
            // posted values
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $promotion_price = $_POST['promotion_price'];
            $manufacture_date = $_POST['manufacture_date'];
            $expired_date = $_POST['expired_date'];
            $product_cat = $_POST['category'];
            $errors = [];
            //Check name
            if (empty($name)) {
                $errors[] = 'Name is required.';
            }
            //Check description
            if (empty($description)) {
                $errors[] = 'Description is required.';
            }
            //Check price
            if (empty($price)) {
                $errors[] = 'Price is required.';
            } elseif (!is_numeric($price)) {
                $errors[] = 'Price must be a number.';
            }
            //Check promotion price
            if (!empty($promotion_price) && !is_numeric($promotion_price)) {
                $errors[] = 'Promotion price must be a number.';
            } elseif (!empty($promotion_price) && $promotion_price >= $price) {
                $errors[] = 'Promotion price must be lower than price.';
            }
            //Check manufacture date
            if (empty($manufacture_date)) {
                $errors[] = 'Manufacture Date is required.';
            } else {
                $manufacture_date = date('Y-m-d', strtotime($manufacture_date));
            }
            //Check expired date
            if (empty($expired_date)) {
                $errors[] = 'Expired Date is required.';
            } else {
                $expired_date = date('Y-m-d', strtotime($expired_date));
            }
            //Check Category
            if (empty($product_cat)) {
                $errors[] = "Choose a product category.";
            }
            //If there is errors, show them
            if (!empty($errors)) {
                echo "<div class='alert alert-danger'><ul>";
                foreach ($errors as $error) {
                    echo "<li>{$error}</li>";
                }
                echo "</ul></div>";
            } else {
                // insert query
                $query = "INSERT INTO products SET name=:name, description=:description, price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date, product_cat=:product_cat, created=:created";
                // prepare query for execution
                $stmt = $con->prepare($query);
                // bind the parameters
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':promotion_price', $promotion_price);
                $stmt->bindParam(':manufacture_date', $manufacture_date);
                $stmt->bindParam(':expired_date', $expired_date);
                $stmt->bindParam(':product_cat', $product_cat);
                // specify when this record was inserted to the database
                $created = date('Y-m-d H:i:s');
                $stmt->bindParam(':created', $created);
                // Execute the query
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Product was added.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to save record.</div>";
                }
            }
        }
        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
    }
    ?>
    <div class="container">
        <div class="page-header">
            <h1>Create Product</h1>
        </div>

        <!-- html form to create product will be here -->

        <form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name='description' class='form-control'></textarea></td>
                </tr>

                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><input type='text' name='promotion_price' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><input type='date' name='manufacture_date' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><input type='date' name='expired_date' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Product Category</td>
                    <td>
                        <label for="category">Choose a Category:</label>
                        <select name="category" id="category" class='form-control'>
                            <?php
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                // extract row
                                extract($row);
                                // creating new table row per record
                                echo '<option value="' . $product_cat_id . '">' . $product_cat_name . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='product_listing.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->
</body>

</html>