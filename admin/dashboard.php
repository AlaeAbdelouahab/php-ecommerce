<?php
include '../php/connexion.php';
include '../php/auth.php';

//get nombre total de produits
$stmt = mysqli_query($conn,"SELECT count(id) AS numprod FROM products");
$numproduits = mysqli_fetch_assoc($stmt);
$total_produits = $numproduits['numprod'];

//calculer le revenue total
$total_revenue = 0;
$stmt = mysqli_query($conn, "SELECT SUM(p.price * l.Quantite) AS total_revenue FROM lignedecommande l JOIN products p ON l.Refprod = p.id");
$num = mysqli_fetch_assoc($stmt);
$total_revenue += $num['total_revenue'];

//get le nombre total de clitents
$stmt = mysqli_query($conn,"SELECT count(*) AS total_customers FROM user");
$num = mysqli_fetch_assoc($stmt);
$total_customers = $num['total_customers'];

//get all commandes
$stmt = mysqli_query($conn, "SELECT c.id, c.Date, c.NumClt, c.status, c.prixtotal, u.firstname, u.lastname FROM commande c JOIN user u ON c.NumClt = u.idu");
$commandes = [];
while($row = mysqli_fetch_assoc($stmt)){
    $commandes[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PinkShop</title>
    <link rel="stylesheet" href="../style/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="../Client/Home.php"><h1 class="admin-logo">PhpShop</h1></a>
                <span class="admin-title">Admin Panel</span>
            </div>
            
            <nav class="sidebar-nav">
                <ul class="nav-list">
                    <li class="nav-item active">
                        <a href="dashboard.php" class="nav-link"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
                    </li>
                    <li class="nav-item">
                        <a href="products.php" class="nav-link"><i class="fas fa-box"></i><span>products</span></a>
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
                <div class="header-actions">
                    <div class="admin-profile">
                        <div class="profile-info">
                            <span class="profile-name">Admin User</span>
                            <span class="profile-role">Administrator</span>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Dashboard Content -->
            <div class="dashboard">
                <div class="page-header">
                    <h2 class="page-title">Dashboard</h2>
                </div>
                
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: rgba(236, 72, 153, 0.1); color: #ec4899">
                            <i class="fas fa-shopping-bag" style="color: var(--pink-500);"></i>
                        </div>
                        <div class="stat-info">
                            <h3 class="stat-value"><?= $total_produits ?></h3>
                            <p class="stat-label">Total Products</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: rgba(16, 185, 129, 0.1); color: #10b981;">
                            <i class="fas fa-chart-line" style="color: var(--green-500);"></i>
                        </div>
                        <div class="stat-info">
                            <h3 class="stat-value"><?= $total_revenue ?></h3>
                            <p class="stat-label">$total_revenue</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                            <i class="fas fa-users" style="color: var(--yellow-500);"></i>
                        </div>
                        <div class="stat-info">
                            <h3 class="stat-value"><?= $total_customers ?></h3>
                            <p class="stat-label">Total Customers</p>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Orders -->
                <div class="card recent-orders">
                    <div class="card-header">
                        <h3 class="card-title">Recent Orders</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($commandes as $commande): ?>
                                        <tr>
                                            <td><?= $cammande['c.id'] ?></td>
                                            <td>
                                                <div class="customer-info">
                                                    <span><?= $commande['u.firstname']. ' '. $commande['u.lastname'] ?></span>
                                                </div>
                                            </td>
                                            <td><?= $commande['c.Date'] ?></td>
                                            <td><?= $commande['c.prixtotal'] ?></td>
                                            <td><span class="status-badge completed"><?= $commande['c.status'] ?></span></td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="#" class="action-btn view-btn" title="View Order">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
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