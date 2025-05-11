<?php 
require_once '../php/connexion.php';
include '../php/auth.php';


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
                 <!-- Products Table -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Products</h3>
                        <div class="header-search">
                            <button type="submit"><i class="fas fa-search"></i></button>
                            <input type="search" placeholder="Search...">
                        </div>
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
                
                