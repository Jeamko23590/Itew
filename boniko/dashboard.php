<?php
session_start();

// Check if the user is logged in, if not try using the cookie to log them in, otherwise redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    if (isset($_COOKIE["user"])) {
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $_COOKIE["user"];
    } else {
        header("location: index.php");
        exit;
    }
}

// Renew the session cookie for 30 more days
setcookie("user", $_SESSION["username"], time() + (86400 * 30), "/");

// Database connection
require 'db_config.php'; // Database connection logic

// Fetch products from the database
function fetchProducts($conn) {
    $products = [];
    $sql = "SELECT product_id, productname, productcategory, quantity, availability, image FROM products";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    return $products;
}

$products = fetchProducts($conn);

// Handle form submission for adding new products
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $productname = trim($_POST['productname']);
    $productcategory = trim($_POST['productcategory']);
    $quantity = (int)$_POST['quantity'];
    $availability = isset($_POST['availability']) ? 1 : 0; // Boolean value for availability

    // Handle image upload
    $image = $_FILES['image']['name'];
    $target_dir = "Images/";
    $target_file = $target_dir . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        // Insert product into database
        $sql = "INSERT INTO products (productname, productcategory, quantity, availability, image) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssisi", $productname, $productcategory, $quantity, $availability, $image);
            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Product added successfully!</div>";
                // Fetch updated product list
                $products = fetchProducts($conn);
            } else {
                echo "<div class='alert alert-danger'>Error adding product: " . $stmt->error . "</div>";
            }
            $stmt->close();
        } else {
            echo "<div class='alert alert-danger'>Error preparing statement: " . $conn->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Error uploading image.</div>";
    }
}

// Handle form submission for updating products
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) {
    $product_id = (int)$_POST['product_id'];
    $productname = trim($_POST['productname']);
    $productcategory = trim($_POST['productcategory']);
    $quantity = (int)$_POST['quantity'];
    $availability = isset($_POST['availability']) ? 1 : 0; // Boolean value for availability

    // Handle image upload (optional)
    $image = $_FILES['image']['name'];
    $target_dir = "Images/";
    $target_file = $target_dir . basename($image);

    if ($image) { // If a new image is uploaded, update it
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        $sql = "UPDATE products SET productname=?, productcategory=?, quantity=?, availability=?, image=? WHERE product_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiisi", $productname, $productcategory, $quantity, $availability, $image, $product_id);
    } else { // If no new image is uploaded, keep the existing one
        $sql = "UPDATE products SET productname=?, productcategory=?, quantity=?, availability=? WHERE product_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiii", $productname, $productcategory, $quantity, $availability, $product_id);
    }

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Product updated successfully!</div>";
        // Fetch updated product list
        $products = fetchProducts($conn);
    } else {
        echo "<div class='alert alert-danger'>Error updating product: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

// Handle deletion of products
if (isset($_GET['delete'])) {
    $product_id = (int)$_GET['delete'];
    $sql = "DELETE FROM products WHERE product_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Product deleted successfully!</div>";
        // Fetch updated product list
        $products = fetchProducts($conn);
    } else {
        echo "<div class='alert alert-danger'>Error deleting product: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoe Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <img style="width: 100px; cursor: pointer;" src="Images/logo.jpg" class="logo">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a style="color: #CE1126;" class="nav-link active" aria-current="page" href="dashboard.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a style="color: #CE1126;" class="nav-link" href="#">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a style="color: #CE1126;" class="nav-link" href="#">Contact Us</a>
                    </li>
                </ul>
                <span class="navbar-text" style="margin-right: 20px;">
                    <a href="profile.php" style="color: #CE1126; text-decoration: none;">
                        <?php echo htmlspecialchars($_SESSION["username"]); ?>
                    </a>
                </span>
                <a href="logout.php" class="btn btn-outline-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Shoe Dashboard</h2>

        <!-- Form to add new products -->
        <form method="POST" enctype="multipart/form-data" class="mb-4">
            <h4>Add New Product</h4>
            <div class="mb-3">
                <label for="productname" class="form-label">Product Name</label>
                <input type="text" class="form-control" name="productname" id="productname" required>
            </div>
            <div class="mb-3">
                <label for="productcategory" class="form-label">Product Category</label>
                <input type="text" class="form-control" name="productcategory" id="productcategory" required>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" name="quantity" id="quantity" required>
            </div>
            <div class="mb-3">
                <label for="availability" class="form-label">Available</label>
                <input type="checkbox" name="availability" id="availability" checked>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" class="form-control" name="image" required>
            </div>
            <button type="submit" name="add_product" class="btn btn-danger">Add Product</button>
        </form>

        <!-- Product List -->
        <h4>Product List</h4>
        <div class="row">
            <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="Images/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['productname']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['productname']); ?></h5>
                        <p class="card-text">Category: <?php echo htmlspecialchars($product['productcategory']); ?></p>
                        <p class="card-text">Quantity: <?php echo htmlspecialchars($product['quantity']); ?></p>
                        <p class="card-text">Available: <?php echo $product['availability'] ? 'Yes' : 'No'; ?></p>
                        <a href="edit_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-primary">Edit</a>
                        <a href="?delete=<?php echo $product['product_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
