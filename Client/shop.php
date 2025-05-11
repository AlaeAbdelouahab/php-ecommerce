<?php
include '../php/connexion.php';
include '../php/auth.php';


    // Récupération des données du formulaire ou valeurs par défaut
    $Category_choisis = $_GET['category'] ?? ($_POST['Category-choisis'] ?? '');
    $Marque_choisis = $_POST['Marque-choisis'] ?? '';
    $Age_choisis = $_POST['Age-choisis'] ?? '';

    // Requête
    $sql = "SELECT * FROM products WHERE 
            (? = '' OR category = ?) AND 
            (? = '' OR brand = ?) AND 
            (? = '' OR agegroupe = ?)";

    if(isset($_SESSION['sort'])){
        $sort = $_SESSION['sort'];
        if ($sort === 'price-low') {
            $sql .= " ORDER BY price ASC";
        } elseif ($sort === 'price-high') {
            $sql .= " ORDER BY price DESC";
        }
    }
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss",$Category_choisis, $Category_choisis, $Marque_choisis, $Marque_choisis, $Age_choisis, $Age_choisis);

    $stmt->execute();
    $result = $stmt->get_result();
    $produits = [];
    while ($row = $result->fetch_assoc()) {
        $produits[] = $row;
    }


//sort items
if(isset($_POST['sort-select'])){

    $sort = $_POST['sort-select'];

    $Category_choisis = $_GET['category'] ?? ($_SESSION['user_filters']['category'] ?? '');
    $Marque_choisis = $_SESSION['user_filters']['brand'] ?? '';
    $Age_choisis = $_SESSION['user_filters']['age'] ?? '';

        $sql = "SELECT * FROM products WHERE 
            (? = '' OR category = ?) AND 
            (? = '' OR brand = ?) AND 
            (? = '' OR agegroupe = ?)";

        if ($sort === 'price-low') {
            $sql .= " ORDER BY price ASC";
        } elseif ($sort === 'price-high') {
            $sql .= " ORDER BY price DESC";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss",$Category_choisis, $Category_choisis, $Marque_choisis, $Marque_choisis, $Age_choisis, $Age_choisis);

        $stmt->execute();
        $result = $stmt->get_result();
        $produits = [];
        while ($row = $result->fetch_assoc()) {
            $produits[] = $row;
        }
        $_SESSION['sort'] = $sort;
}

//number of items in the user basket
$user_id = null;
if (isset($_SESSION['idu'])) {
    $user_id = $_SESSION['idu'];
} 
if (isset($_SESSION['admin'])){
    $admin = 1;
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
    <title>Shop - PhpShop</title>
    <link rel="stylesheet" href="../style/shop-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="page-container">
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo"><a href="Home.php">PhpShop</a></div>

                <div class="search-bar">
                    <input type="search" placeholder="Search products...">
                    <button class="search-button"><i class="fas fa-search"></i></button>
                </div>

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
            <div class="shop-layout">
                <!-- Sidebar filters -->
                <form method="POST" action="shop.php">
                    <h4 class="filter-header">Categories</h4>
                    <div class="filter-body">
                        <div class="radio-group">
                            <div class="radio-item"><input type="radio" name="Category-choisis" value="Dress">Dresses</div>
                            <div class="radio-item"><input type="radio" name="Category-choisis" value="Top">Tops</div>
                            <div class="radio-item"><input type="radio" name="Category-choisis" value="Bottom">Bottoms</div>
                            <div class="radio-item"><input type="radio" name="Category-choisis" value="WinterWear">Winter wear</div>
                        </div>
                    </div>

                    <h4 class="filter-header">Marques</h4>
                    <div class="filter-body" id="Marque-filter">
                        <div class="radio-group">
                            <div class="radio-item"><input type="radio" name="Marque-choisis" value="PrettyLittleThing">PrettyLittleThing</div>
                            <div class="radio-item"><input type="radio" name="Marque-choisis" value="Zaful">Zaful</div>
                            <div class="radio-item"><input type="radio" name="Marque-choisis" value="Shein">Shein</div>
                            <div class="radio-item"><input type="radio" name="Marque-choisis" value="LevantSwan">LevantSwan</div>
                        </div>
                    </div>

                    <h4 class="filter-header">Age</h4>
                    <div class="filter-body">
                        <div class="radio-group">
                            <div class="radio-item"><input type="radio" name="Age-choisis" value="Enfant">Enfant</div>
                            <div class="radio-item"><input type="radio" name="Age-choisis" value="Adolescent">Adolescent</div>
                            <div class="radio-item"><input type="radio" name="Age-choisis" value="Adulte">Adulte</div>
                        </div>
                    </div>

                    <input type="submit" value="Apply" name="apply" class="filter-apply-button"/>
                </form>

                <!-- Product Grid -->
                <div class="shop-content">
                    <form method=POST action="">
                        <div class="filter-bar">
                            <div class="filter">
                                <i class="fas fa-sliders-h"></i><span>Filters</span>
                            </div>
                            <div class="sort-options">
                                <span class="sort-label">Sort by:</span>
                                <div class="select-wrapper">
                                    <select name="sort-select">
                                        <option value="price-low">Featured</option>
                                        <option value="price-low">Price: Low to High</option>
                                        <option value="price-high">Price: High to Low</option>
                                    </select>
                                </div>
                                <button type="submit" class="sort-apply-button">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="product-grid">
                        <?php 
                        foreach ($produits as $produit): ?>
                            <div class='product-card'>
                                <a href='product-details.php?id=<?= $produit['id'] ?>' class='product-image'>
                                    <img src='../images/ecom-products/<?= $produit['category'] ?>/<?= $produit['imageURL'] ?>'>
                                </a>
                                <div class='product-info'>
                                    <a href='product-details.php?id=<?= $produit['id'] ?>' class='product-name'><?= $produit['name'] ?></a>
                                    <?php if($produit['quantity']==0){
                                        echo '<p style="color: red; font-style: italic;">OUT OF STOCK </p>';
                                        }
                                    ?>
                                    <div class='product-price-actions'>
                                        <span class='product-price'><?= $produit['price'] ?> DH</span>
                                        <form method='POST' action='add.php'>
                                            <input type='hidden' name='id' value='<?= $produit['id'] ?>'>
                                            <input type='hidden' name='nom' value='<?= $produit['name'] ?>'>
                                            <input type='hidden' name='prix' value='<?= $produit['price'] ?>'>
                                            <input type='hidden' name='quantite' value='1'>
                                            <button type='submit' class='add-to-cart'>
                                                <i class='fas fa-plus-circle'></i>
                                                Add
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div> 
                </div>
            </div> 
        </div> 
    </main> 
</div>
</body>
</html>

