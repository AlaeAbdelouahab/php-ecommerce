<?php
// Include database connection
require_once '../php/connexion.php';

// Initialize variables
$name = $description = $category = $brand = $age_group = $price = $quantity = "";
$name_err = $description_err = $category_err = $brand_err = $age_group_err = $price_err = $image_err = $quantity_err = "";
$success_message = $error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_product"])) {
    
    if (empty($_POST["name"])) {
        $name_err = "Please enter a product name.";
    } else {
        $name = $_POST["name"];
    }
    
    if (empty($_POST["description"])) {
        $description_err = "Please enter a product description.";
    } else {
        $description = $_POST["description"];
    }
    
    if (empty($_POST["category"])) {
        $category_err = "Please select a category.";
    } else {
        $category = $_POST["category"];
    }
    
    if (empty($_POST["brand"])) {
        $brand_err = "Please enter a brand.";
    } else {
        $brand = $_POST["brand"];
    }
    
    if (empty($_POST["age_group"])) {
        $age_group_err = "Please select an age group.";
    } else {
        $agegroupe = $_POST["age_group"];
    }
    
    if (empty($_POST["price"])) {
        $price_err = "Please enter a price.";
    } elseif (!is_numeric($_POST["price"]) || floatval($_POST["price"]) <= 0) {
        $price_err = "Please enter a valid price.";
    } else {
        $price = $_POST["price"];
    }

    if (empty($_POST["quantity"])) {
        $quantity_err = "Please enter a quantity.";
    } elseif (!is_numeric($_POST["quantity"]) || floatval($_POST["price"]) <= 0) {
        $quantity_err = "Please enter a valid quantity.";
    } else {
        $quantity = $_POST["quantity"];
    }
    
    if (!isset($_FILES["image"])) {
        $image_err = "Please select an image.";
    } else {
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        $file_tmp = $_FILES["image"]["tmp_name"];
        $file_name = $_FILES["image"]["name"];
        $file_size = $_FILES["image"]["size"];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        if (!in_array($file_ext, $allowed_types)) {
            $image_err = "Only JPG, JPEG, PNG & GIF files are allowed.";
        } elseif ($file_size > 5000000) {
            $image_err = "File size must be less than 5MB.";
        }

    }

    if (empty($name_err) && empty($description_err) && empty($category_err) && empty($brand_err) && empty($age_group_err) && empty($price_err) && empty($image_err)) {
        
        $sql = "INSERT INTO products (name, description, category, brand, agegroupe, price, imageURL, quantity) VALUES (?, ?, ?, ?, ?, ?, ?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssdsd", $name, $description, $category, $brand, $agegroupe, $price, $file_name, $quantity);
        
        $imfold = "../images/ecom-products/$category/";

        if ($stmt->execute()) {
            if (move_uploaded_file($file_tmp, $imfold . $file_name)) {
                $success_message = "Product added successfully.";
            } else {
                $error_message = "Error uploading image.";
            }
        } else {
            $error_message = "Something went wrong. Please try again later.";
        }

        $stmt->close();
    }
}


// Delete a product
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    
    $sql = "SELECT imageURL, category FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($image, $category);
    $stmt->fetch();
    $stmt->close();
    
    // Delete the product
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
        
    if ($stmt->execute()) {
        // Delete the image file if it exists
        $images_location = "../images/ecom-products/$category/";
        if (!empty($image) && file_exists($images_location . $image)) {
            unlink($images_location . $image);
        }
        $success_message = "Product deleted successfully.";
    } else {
        $error_message = "Something went wrong. Please try again later.";
    }
    
    $stmt->close();

}

// Fetch all products
$products = array();
$sql = "SELECT id, name, description, category, brand, agegroupe, price, imageURL FROM products ORDER BY id DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

//search for products
if(isset($_POST['search'])){
    $products = [];
    $search = "%". $_POST['search']. "%";
    $query = "SELECT * from products WHERE name LIKE ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $products[] = $row;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Admin Dashboard - PinkShop</title>
    <link rel="stylesheet" href="../style/admin.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="../Client/Home.php" class="admin-logo">PinkShop</a></br>
                <span class="admin-title">Admin Panel</span>
            </div>
            
            <nav class="sidebar-nav">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
                    </li>
                    <li class="nav-item active">
                        <a href="products.php" class="nav-link"><i class="fas fa-box"></i><span>products</span></a>
                    </li>
                    <li class="nav-item">
                        <a href="customers.php" class="nav-link"><i class="fas fa-users"></i><span>Customers</span></a>
                    </li>
            </nav>
            
            <div class="sidebar-footer">
                <a href="../php/logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="admin-header">
                <div class="admin-profile">
                    <div class="profile-info">
                        <span class="profile-name">Admin User</span>
                        <span class="profile-role">Administrator</span>
                    </div>
                </div>
            </header>
            
            <!-- Products Content -->
            <div class="dashboard">
                <div class="page-header">
                    <h2 class="page-title">Products</h2>
                    <div class="page-actions">
                        <button class="btn btn-primary" onclick="document.getElementById('add-product-form').style.display='block'">
                            <i class="fas fa-plus"></i> Add New Product
                        </button>
                    </div>
                </div>
                
                <?php if (!empty($success_message)): ?>
                <div class="alert alert-success">
                    <?php echo $success_message; ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($error_message)): ?>
                <div class="alert alert-error">
                    <?php echo $error_message; ?>
                </div>
                <?php endif; ?>
                
                <!-- Add Product Form -->
                <div class="form-card" id="add-product-form" style="display: none;">
                    <div class="form-header">
                        <h3 class="form-title">Add New Product</h3>
                    </div>
                    <div class="form-body">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" name="name" class="form-input <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>">
                                    <span class="invalid-feedback"><?php echo $name_err; ?></span>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Category</label>
                                    <select name="category" class="form-select <?php echo (!empty($category_err)) ? 'is-invalid' : ''; ?>">
                                        <option value="">Select Category</option>
                                        <option value="Dress">Dress</option>
                                        <option value="Top">Top</option>
                                        <option value="Bottom">Bottom</option>
                                        <option value="WinterWear">WinterWear</option>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $category_err; ?></span>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Brand</label>
                                    <input type="text" name="brand" class="form-input <?php echo (!empty($brand_err)) ? 'is-invalid' : ''; ?>">
                                    <span class="invalid-feedback"><?php echo $brand_err; ?></span>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Age Group</label>
                                    <select name="age_group" class="form-select <?php echo (!empty($age_group_err)) ? 'is-invalid' : ''; ?>">
                                        <option value="">Select Age Group</option>
                                        <option value="kids" <?php echo ($age_group == "Enfant") ? 'selected' : ''; ?>>Enfant</option>
                                        <option value="teens" <?php echo ($age_group == "Adolescent") ? 'selected' : ''; ?>>Adolescent</option>
                                        <option value="adults" <?php echo ($age_group == "Adulte") ? 'selected' : ''; ?>>Adulte</option>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $age_group_err; ?></span>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Price</label>
                                    <input type="text" name="price" class="form-input <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>">
                                    <span class="invalid-feedback"><?php echo $price_err; ?></span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Quantity</label>
                                    <input type="text" name="quantity" class="form-input <?php echo (!empty($quantity_err)) ? 'is-invalid' : ''; ?>">
                                    <span class="invalid-feedback"><?php echo $quantity_err; ?></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-textarea <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"></textarea>
                                <span class="invalid-feedback"><?php echo $description_err; ?></span>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Product Image</label>
                                <div class="file-upload">
                                    <div class="file-input-wrapper">
                                        <button type="button" class="file-input-btn">
                                            <i class="fas fa-upload"></i> Choose Image
                                        </button>
                                        <input type="file" name="image" class="file-input <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>">
                                    </div>
                                    <span class="invalid-feedback"><?php echo $image_err; ?></span>
                                </div>
                            </div>
                            
                            <div class="form-footer">
                                <button type="button" class="btn btn-secondary" onclick="document.getElementById('add-product-form').style.display='none'">Cancel</button>
                                <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Products Table -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Products</h3>
                        <form method="POST" action="">
                            <div class="header-search">
                                <button type="submit" ><i class="fas fa-search"></i></button>
                                <input type="search" name="search" placeholder="Search...">
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Brand</th>
                                        <th>Age Group</th>
                                        <th>Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?php echo $product['id']; ?></td>
                                        <td>
                                            <img src="../images/ecom-products/<?= $product['category'] ?>/<?= $product['imageURL'] ?>" alt="<?php echo $product['name']; ?>" width="50" height="50" style="object-fit: cover; border-radius: var(--radius);">
                                        </td>
                                        <td><?= $product['name'] ?></td>
                                        <td><?= $product['category'] ?></td>
                                        <td><?= $product['brand'] ?></td>
                                        <td><?= $product['agegroupe'] ?></td>
                                        <td><?= $product['price'] ?>DH</td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="edit-product.php?id=<?= $product['id']; ?>" class="action-btn edit-btn" title="Edit Product">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="products.php?delete=<?= $product['id']; ?>" class="action-btn delete-btn" title="Delete Product" onclick="return confirm('Are you sure you want to delete this product?');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($products)): ?>
                                    <tr>
                                        <td colspan="8" style="text-align: center;">No products found.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>