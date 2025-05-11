<?php
// Include database connection
require_once '../php/connexion.php';
include '../php/auth.php';

// Initialize variables
$success_message = $error_message = "";

// Process request when delete button is clicked
if (isset($_GET["delete"]) && !empty($_GET["delete"])) {
    $id = $_GET["delete"];
    $sql = "DELETE FROM user WHERE idu = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $success_message = "User deleted successfully.";
        } else {
            $error_message = "Something went wrong. Please try again later.";
        }
    }
}

// Fetch all users
$users = array();
$sql = "SELECT idu, firstname, lastname, email FROM user ORDER BY idu DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - Admin Dashboard - PhpShop</title>
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
                        <a href="dashboard.php" class="nav-link"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
                    </li>
                    <li class="nav-item">
                        <a href="products.php" class="nav-link"><i class="fas fa-box"></i><span>Products</span></a>
                    </li>
                    <li class="nav-item active">
                        <a href="customers.php" class="nav-link"><i class="fas fa-users"></i><span>Customers</span></a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn">
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
            
            <!-- Customers Content -->
            <div class="dashboard">
                <div class="page-header">
                    <h2 class="page-title">Customers</h2>
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
                
                <!-- Customers Table -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Customers</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Firstname</th>
                                        <th>Lastname</th>
                                        <th>email</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo $user['idu']; ?></td>
                                        <td><?php echo $user['firstname']; ?></td>
                                        <td><?php echo $user['lastname']; ?></td>
                                        <td><?php echo $user['email']; ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="edit-customer.php?id=<?php echo $user['idu']; ?>" class="action-btn edit-btn" title="Edit Customer">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="customers.php?delete=<?php echo $user['idu']; ?>" class="action-btn delete-btn" title="Delete Customer" onclick="return confirm('Are you sure you want to delete this customer?');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="8" style="text-align: center;">No customers found.</td>
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