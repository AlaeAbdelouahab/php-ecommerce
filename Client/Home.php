<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweet Girls Fashion - Fashion & Accessories</title>
    <link rel="stylesheet" href="../style/home-styles.css">
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
                        <a href="index.html">Sweet Girls Fashion</a>
                    </div>

                    <!-- Search Bar -->
                    <div class="search-bar">
                        <input type="search" placeholder="Search products...">
                        <button class="search-button">
                            <i class="fas fa-search"></i>
                            <span class="sr-only">Search</span>
                        </button>
                    </div>

                    <!-- Icons -->
                    <div class="header-icons">
                        <a href="login.php" class="icon-button">
                            <i class="fas fa-user"></i>
                            <span class="sr-only">Account</span>
                        </a>
                        <a href="panier.php" class="icon-button cart-icon">
                            <i class="fas fa-shopping-basket"></i>
                            <span class="sr-only">Cart</span>
                            <span class="badge">0</span>
                        </a>
                    </div>
                </div>

                <!-- Navigation menu -->
                <nav class="main-nav">
                    <ul class="nav-list">
                        <li class="nav-item active"><a href="index.html">Home</a></li>
                        <li class="nav-item"><a href="shop.php">Shop</a></li>
                        <li class="nav-item"><a href="#Categories">Categories</a></li>
                        <li class="nav-item"><a href="about.php">About</a></li>
                        <li class="nav-item"><a href="#">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </header>

        <main class="main-content">
            <!-- Welcome section -->
            <section class="Welcome-section">
                <div class="container">
                    <div class="Welcome-content">
                        <h1 class="Welcome-title">Welcome to Sweet Girls Fashion</h1>
                        <p class="Welcome-subtitle">Discover the latest trends in fashion and accessories</p>
                        <a href="shop.php" class="Welcome-button">Shop Now</a>
                    </div>
                </div>
            </section>

            <!-- Categories Section -->
            <section class="categories-section" id="Categories">
                <div class="container">
                    <h2 class="section-title">Shop by Category</h2>
                    <div class="categories-grid">
                        <a href="shop.php?category=Dress" class="category-card">
                            <div class="category-image">
                                <img src="../images/Dresses.png">
                            </div>
                            <h3 class="category-title">Dresses</h3>
                        </a>
                        <a href="shop.php?category=Top" class="category-card">
                            <div class="category-image">
                                <img src="../images/Tops.png">
                            </div>
                            <h3 class="category-title">Tops</h3>
                        </a>
                        <a href="shop.php?category=Bottom" class="category-card">
                            <div class="category-image">
                                <img src="../images/Bottoms.png">
                            </div>
                            <h3 class="category-title">Bottoms</h3>
                        </a>
                        <a href="shop.php?category=WinterWear" class="category-card">
                            <div class="category-image">
                                <img src="../images/Footwear.png">
                            </div>
                            <h3 class="category-title">WinterWear</h3>
                        </a>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
