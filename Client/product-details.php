<?php
include '../php/connexion.php';
include '../php/auth.php';

$product_id = $_GET['id'] ?? null;
$result = mysqli_query($conn, "SELECT * FROM products WHERE id=$product_id");
$row = mysqli_fetch_assoc($result);

$user_id = null;
if (isset($_SESSION['idu'])) {
    $user_id = $_SESSION['idu'];
} 
$admin=null;
if (isset($_SESSION['admin'])){
    $admin = 1;
}
if($user_id){
    $numprod = mysqli_query($conn, "SELECT count(*) AS product_count FROM basket WHERE user_id=$user_id");
    $num = mysqli_fetch_assoc($numprod);
    $i = $num['product_count'];
} else {$i = 0;}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //add item to basket
    $quantity = $_POST['quantity'] ?? null;
    $color = $_POST['color'] ?? null;
    $size = $_POST['size'] ?? null;
    // Check session
    if (!$user_id){
            echo "You need to be logged in to add items to your basket.";
            exit; 
    }elseif($product_id && $color && $size && $quantity) {
        $stmt = $conn->prepare("SELECT product_id FROM basket WHERE user_id = ? AND product_id = ? AND color = ? AND size = ?");
        $stmt->bind_param("iiss", $user_id, $product_id, $color, $size);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE basket SET quantity = quantity + ? WHERE user_id = ? AND product_id = ? AND color = ? AND size = ?");
            $stmt->bind_param("iiiss", $quantity, $user_id, $product_id, $color, $size);
            if ($stmt->execute()) {
                $message = "Product added to the basket.";
            } else {
                $message = "Error adding product to basket.";
            }
        } else {

            $stmt = $conn->prepare("INSERT INTO basket (user_id, product_id, color, size, quantity) 
                                    VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iisss", $user_id, $product_id, $color, $size, $quantity);

            if ($stmt->execute()) {
                $message = "Product added to the basket.";
            } else {
                $message = "Error adding product to basket.";
            }

            $stmt->close();
        }
    }
}

if($user_id){
    $numprod = mysqli_query($conn, "SELECT count(*) AS product_count FROM basket WHERE user_id=$user_id");
    $num = mysqli_fetch_assoc($numprod);
    $i = $num['product_count'];
} else {$i = 0;}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Product Detail - PinkShop</title>
<link rel="stylesheet" href="../style/product-detail.css">
<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="page-container">
    <!-- Header -->
    <header class="header">
            <div class="container">
                <div class="header-content">
                    <!-- Logo -->
                    <div class="logo">
                        <a href="Home.php">PhpShop</a>
                    </div>

                    <!-- Icons -->
                    <div class="header-icons">
                        <?php if($user_id){echo '<a href="dashboard.php?id = '. $user_id. '" class="icon-button"><i class="fas fa-user"></i></a>';
                              } elseif($admin) {echo '<a href="../admin/dashboard.php" class="icon-button">admin dashboard</a><i class="fas fa-user"></i>';
                              } else {echo '<a href="login.php" class="icon-button"><i class="fas fa-user"></i></a>';} ?>
                        <?php if(!$admin){ echo '<a href="panier.php" class="icon-button cart-icon">
                            <i class="fas fa-shopping-basket"></i>
                            <span class="sr-only">Cart</span>
                            <span class="badge">'. $i. '</span>
                            </a>';} 
                        ?>
                    </div>
                </div>
            </div>
    </header>

    <main class="main-content">
        <div class="container">
            <!-- Breadcrumbs -->
            <nav class="breadcrumbs">
                <ol class="breadcrumb-list">
                    <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="shop.php">Shop</a></li>
                    <li class="breadcrumb-item active">Summer Dress</li>
                </ol>
            </nav>

            <!-- Product Detail -->
            <div class="product-detail">
                <!-- Product Images -->
                <div class="product-images">
                    <div class="main-image">
                    <img src='../images/ecom-products/<?= $row['category'] ?>/<?= $row['imageURL'] ?>'>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="product-info">
                    <h1 class="product-title"><?= $row['name'] ?></h1>
                    
                    <div class="product-meta">
                        <div class="product-rating">
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="rating-count">42 reviews</span>
                        </div>
                    </div>

                    <div class="product-pricing">
                        <div class="current-price"><?= $row['price'] ?>DH</div>

                    </div>

                    <div class="product-description">
                        <p><?= $row['description'] ?></p>
                    </div>
                    <form method="POST" action="">
                        <div class="product-options">
                            <!-- Color Options -->
                            <div class="option-group">
                                <label class="option-label">Color: <span class="selected-option">black</span></label>
                                <div class="color-options">
                                    <label class="color-option">
                                        <input type="radio" name="color" value="white" id="color" checked>
                                        <span class="color-swatch" style="background-color:rgb(255, 255, 255);"></span>
                                    </label>
                                    <label class="color-option">
                                        <input type="radio" name="color" value="black" id="color">
                                        <span class="color-swatch" style="background-color:rgb(0, 0, 0);"></span>
                                    </label>
                                    <label class="color-option">
                                        <input type="radio" name="color" value="green" id="color">
                                        <span class="color-swatch" style="background-color: #ec4899;"></span>
                                    </label>
                                </div>
                            </div>

                            <!-- Size Options -->
                            <div class="option-group">
                                <label class="option-label">Size: <span class="selected-option">M</span></label>
                                <div class="size-options">
                                    <label class="size-option">
                                        <input type="radio" name="size" value="xs" id="size">
                                        <span class="size-label">XS</span>
                                    </label>
                                    <label class="size-option">
                                        <input type="radio" name="size" value="s" id="size">
                                        <span class="size-label">S</span>
                                    </label>
                                    <label class="size-option">
                                        <input type="radio" name="size" value="m" checked id="size">
                                        <span class="size-label">M</span>
                                    </label>
                                    <label class="size-option">
                                        <input type="radio" name="size" value="l" id="size">
                                        <span class="size-label">L</span>
                                    </label>
                                    <label class="size-option">
                                        <input type="radio" name="size" value="xl" id="size">
                                        <span class="size-label">XL</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Quantity -->
                            <div class="option-group">
                                <?php if($row['quantity']==0){
                                        echo '<p style="color: red; font-style: italic;">OUT OF STOCK </p>';
                                        }else{
                                            echo '<div>Available Quantity: '. $row['quantity']. '</div>';
                                            echo '<label class="option-label">Quantity:</label>';
                                            echo '<input type="number" name="quantity" value="1" min="1" max="'. $row['quantity']. '" step="1" class="quantity-input" id="quantity">';
                                        }
                                    ?>
                            </div>
                        </div>

                        <div class="product-actions">
                            <button class="add-to-cart-btn" type="submit">
                                <i class="fas fa-shopping-basket"></i>
                                Add to Cart
                            </button>
                        </div>
                    </form>
                    <div class="product-delivery">
                        <div class="delivery-option">
                            <i class="fas fa-truck"></i>
                            <span>Free shipping on orders over 500DH</span>
                        </div>
                        <div class="delivery-option">
                            <i class="fas fa-undo"></i>
                            <span>30-day return policy</span>
                        </div>
                        <a href="shop.php" class="back-link">← Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php if (!empty($message)){
        echo '<div id="flash-message">'. $message. '</div>';
        } ?>
    <script>
        setTimeout(() => {
            document.getElementById("flash-message").style.display = "none";
        }, 2000);
    </script>
</div>
</body>
</html>