<?php
require_once '../php/connexion.php';
include '../php/auth.php';

$firstname = $lastname = $email = "";
$firstname_err = $lastname_err = $email_err = "";
$success_message = $error_message = "";

// get customer informations
if (isset($_GET["id"]) && !empty($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "SELECT * FROM user WHERE idu = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $firstname = $row["firstname"];
            $lastname = $row["lastname"];
            $email = $row["email"];
        } else {
            header("location: customers.php");
            exit();
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
} else {
    header("location: customers.php");
    exit();
}

// Edit the customer
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (empty($_POST["firstname"])) {
        $firstname_err = "Please enter a name.";
    } else {
        $firstname = $_POST["firstname"];
    }
    
    if (empty($_POST["lastname"])) {
        $lastname_err = "Please enter a name.";
    } else {
        $lastname = $_POST["lastname"];
    }
    
    $pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,}$/";
    if (empty($_POST["email"])) {
        $email_err = "Please enter an email.";
    } elseif (!preg_match($pattern, $_POST['email'])) {
        $email_err = "Please enter a valid email address.";
    } else {
        $email = $_POST["email"];
        // Check if email exists already
        $sql = "SELECT idu FROM user WHERE email = ? AND idu != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $email, $id);
        
        if ($stmt->execute()) {
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $email_err = "This email is already used.";
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    
    // Check input errors before updating the database
    if (empty($firstname_err) && empty($lastname_err) && empty($email_err)) {

        $sql = "UPDATE user SET firstname = ?, lastname=?, email = ? WHERE idu = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $firstname, $lastname, $email, $id);
        if ($stmt->execute()) {
            $success_message = "Customer updated successfully.";
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
    <title>Edit Customer - Admin Dashboard - PinkShop</title>
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
            
            <!-- Edit Customer Content -->
            <div class="dashboard">
                <div class="page-header">
                    <h2 class="page-title">Edit Customer</h2>
                    <div class="page-actions">
                        <a href="customers.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Customers
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
                
                <!-- Edit Customer Form -->
                <div class="form-card">
                    <div class="form-header">
                        <h3 class="form-title">Edit Customer: <?php echo $firstname; ?></h3>
                    </div>
                    <div class="form-body">
                        <form action="edit-customer.php?id=<?= $id ?>" method="post">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">firstname</label>
                                    <input type="text" name="firstname" class="form-input <?php echo (!empty($firstname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $firstname; ?>">
                                    <span class="invalid-feedback"><?php echo $firstname_err; ?></span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">lastname</label>
                                    <input type="text" name="lastname" class="form-input <?php echo (!empty($lastname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastname; ?>">
                                    <span class="invalid-feedback"><?php echo $lastname_err; ?></span>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-input <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                                </div>
                            </div>
                            <div class="form-footer">
                                <a href="customers.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Customer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>