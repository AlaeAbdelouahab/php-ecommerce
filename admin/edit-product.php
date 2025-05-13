<?php
// Include database connection
require_once '../php/connexion.php';
include '../php/auth.php';

// Initialize variables
$name = $description = $category = $brand = $age_group = $price = $image = $quantity ="";
$name_err = $description_err = $category_err = $brand_err = $age_group_err = $price_err = $image_err = $quantity_err = "";
$success_message = $error_message = "";

//get product informations
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $name = $row["name"];
        $description = $row["description"];
        $category = $row["category"];
        $brand = $row["brand"];
        $age_group = $row["agegroupe"];
        $price = $row["price"];
        $image = $row["imageURL"];
        $quantity = $row['quantity'];
    } else {
        header("location: products.php");
        exit();
    }
} else {
    header("location: products.php");
    exit();
}

//update product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (empty($_POST["name"])) { $name_err = "Please enter a product name.";} 
    else { $name = $_POST["name"];}
    
    if (empty($_POST["description"])) { $description_err = "Please enter a product description.";}
    else { $description = $_POST["description"];}
    
    if (empty($_POST["category"])) { $category_err = "Please select a category.";} 
    else { $category = $_POST["category"];}
    
    if (empty($_POST["brand"])) { $brand_err = "Please enter a brand.";} 
    else { $brand = $_POST["brand"];}
    
    if (empty($_POST["age_group"])) { $age_group_err = "Please select an age group.";} 
    else { $age_group = $_POST["age_group"];}
    
    if (empty($_POST["price"])) { $price_err = "Please enter a price.";}
    elseif (!is_numeric($_POST["price"]) || floatval($_POST["price"]) <= 0) { $price_err = "Please enter a valid price.";}
    else { $price = $_POST["price"];}

    if (empty($_POST["quantity"])) { $quantity_err = "Please enter a quantity.";}
    elseif (!is_numeric($_POST["quantity"]) || floatval($_POST["quantity"]) <= 0) { $quantity_err = "Please enter a valid quantity.";}
    else { $quantity = $_POST["quantity"];}

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $allowed_types = array('jpg', 'jpeg', 'png');
        $image = $_FILES["image"]["name"];
        //check if the file is an image
        $file_parts = explode('.', $image);
        $file_extension = strtolower(end($file_parts));
        if (!(in_array($file_extension, $allowed_types))) {
            $image_err = 'Only JPG, JPEG, PNG files are allowed:';
        }
    } 
    
    if (empty($name_err) && empty($description_err) && empty($category_err) && empty($brand_err) && empty($age_group_err) && empty($price_err) && empty($image_err)) {
            
        $sql = "UPDATE products SET name = ?, description = ?, category = ?, brand = ?, agegroupe= ?, price = ?, imageURL = ? , quantity = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssdsii", $name, $description, $category, $brand, $age_group, $price, $image, $quantity, $id);
        
        if ($stmt->execute()) {
            $success_message = "Product updated successfully.";
        } else {
            $error_message = "Something went wrong. Please try again later.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Admin Dashboard - PinkShop</title>
    <link rel="stylesheet" href="../style/admin.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1 class="admin-logo">PinkShop</h1>
                <span class="admin-title">Admin Panel</span>
            </div>
            
            <nav class="sidebar-nav">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="dashborad.php" class="nav-link"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
                    </li>
                    <li class="nav-item active">
                        <a href="products.php" class="nav-link"><i class="fas fa-box"></i><span>Products</span></a>
                    </li>
                    <li class="nav-item">
                        <a href="customers.php" class="nav-link"><i class="fas fa-users"></i><span>Customers</span></a>
                    </li>
                </ul>
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
                        <span class="profile-name">Admin</span>
                        <span class="profile-role">Administrator</span>
                    </div>
                </div>
            </header>
            
            <!-- Edit Product Content -->
            <div class="dashboard">
                <div class="page-header">
                    <h2 class="page-title">Edit Product</h2>
                    <div class="page-actions">
                        <a href="products.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Products
                        </a>
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
                
                <!-- Edit Product Form -->
                <div class="form-card">
                    <div class="form-header">
                        <h3 class="form-title">Edit Product: <?php echo $name; ?></h3>
                    </div>
                    <div class="form-body">
                        <form action="edit-product.php?id=<?=$id?>" method="post" enctype="multipart/form-data">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" name="name" class="form-input <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
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
                                    <input type="text" name="brand" class="form-input <?php echo (!empty($brand_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $brand; ?>">
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
                                    <input type="text" name="price" class="form-input <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $price; ?>">
                                    <span class="invalid-feedback"><?php echo $price_err; ?></span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Quantity</label>
                                    <input type="text" name="quantity" class="form-input <?php echo (!empty($quantity_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $quantity; ?>">
                                    <span class="invalid-feedback"><?php echo $quantity_err; ?></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-textarea <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
                                <span class="invalid-feedback"><?php echo $description_err; ?></span>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Current Image</label>
                                <div class="current-image">
                                    <?php if (!empty($image)): ?>
                                    <img src="../images/ecom-products/<?= $category ?>/<?= $image ?>" width="100" height="100" style="object-fit: cover; border-radius: var(--radius);">
                                    <?php else: ?>
                                    <p>No image available</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Change Image (optional)</label>
                                <div class="file-upload">
                                    <div class="file-input-wrapper">
                                        <button type="button" class="file-input-btn">
                                            <i class="fas fa-upload"></i> Choose New Image
                                        </button>
                                        <input type="file" name="image" class="file-input <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>">
                                    </div>
                                    <span class="invalid-feedback"><?php echo $image_err; ?></span>
                                </div>
                            </div>
                            
                            <div class="form-footer">
                                <a href="admin-products.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>