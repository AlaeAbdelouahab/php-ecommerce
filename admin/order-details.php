<?php
include '../php/connexion.php';
include '../php/auth.php';

$order_id = $_GET['id'];
//get order details
$sql = "SELECT order_details.*, products.name as name, (products.price*order_details.quantity) as price FROM order_details JOIN products ON order_details.product_id = products.id WHERE order_details.order_id = $order_id";
$result = $conn->query($sql);
$orders = [];
while($row = mysqli_fetch_assoc($result)){
    $orders[] = $row;
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
                    <h3 class="page-title">Order details: order numbre - <?= $order_id ?> -</h3>
                    <div class="page-actions">
                        <a href="dashboard.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to dashboard
                        </a>
                    </div>
                </div>
                
                <!-- Order details -->
                <div class="card recent-orders">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>product</th>
                                        <th>Quantity</th>
                                        <th>color</th>
                                        <th>size</th>
                                        <th>price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($orders as $order): ?>
                                        <tr>
                                            <td><?= $order['name'] ?></td>
                                            <td><?= $order['quantity'] ?></td>
                                            <td><?= $order['color'] ?></td>
                                            <td><?= $order['size'] ?></td>
                                            <td><?= $order['price'] ?></td>
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