<?php
session_start();

if (!isset($_SESSION['idu'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "phpshop");
if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}

$userId = $_SESSION['idu'];

$stmt = $conn->prepare("SELECT * FROM user WHERE idu = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$orders = [];
$orderStmt = $conn->prepare("
    SELECT orders.*, products.name AS product_name
    FROM orders
    JOIN products ON orders.product_id = products.id
    WHERE orders.user_id = ?
");
$orderStmt->bind_param("i", $userId);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();
while ($order = $orderResult->fetch_assoc()) {
    $orders[] = $order;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <style>
        :root {
            --pink-500: #ec4899;
            --pink-600: #db2777;
            --pink-800: #9d174d;
            --light-pink: rgb(194, 76, 135);
            --lighter-pink: #fbcfe8;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fff0f5;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: var(--light-pink);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .profile-container {
            padding: 30px;
            background-color: #fff;
        }

        .profile-container h2 {
            color: var(--light-pink);
        }

        .profile-info p {
            font-size: 16px;
            color: #333;
        }

        .order-list {
            margin-top: 30px;
        }

        .order-item {
            background-color: #f8d7da;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            border: 1px solid #f5c6cb;
        }

        .order-item h4 {
            color: var(--light-pink);
        }

        .order-item p {
            color: #555;
        }

        .back-link {
            display: inline-block;
            margin: 20px;
            text-decoration: none;
            color: #e0529f;
            font-weight: bold;
        }
    </style>
</head>
<body>

<header>
    <h1>Welcome <?= $user['firstname'] ?>!</h1>
</header>

<div class="profile-container">
    <div class="profile-info">
        <h2>Your Profile</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($user['firstname']) ?> <?= htmlspecialchars($user['lastname']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    </div>

    <div class="order-list">
        <h2>Your Orders</h2>
        <?php if (count($orders) > 0): ?>
            <?php foreach ($orders as $order): ?>
                <div class="order-item">
                    <h4>Order <?= $order['order_id'] ?> - <?= htmlspecialchars($order['product_name']) ?></h4>
                    <p><strong>Quantity:</strong> <?= $order['quantity'] ?></p>
                    <p><strong>Total:</strong> <?= $order['total_price'] ?> MAD</p>
                    <p><strong>Date:</strong> <?= $order['order_date'] ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You have no orders yet.</p>
        <?php endif; ?>
    </div>
</div>

<a href="Home.php" class="back-link">← Continue Shopping</a>

</body>
</html>
