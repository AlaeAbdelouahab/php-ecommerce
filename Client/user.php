<?php
include '../php/connexion.php';
include '../php/auth.php';

if (!isset($_SESSION['idu'])) {
    header("Location: login.php");
    exit;
}


$userId = $_SESSION['idu'];
$cart = [];
$total = 0;

// Infos utilisateur
$stmt = $conn->prepare("SELECT * FROM user WHERE idu = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Commandes passées
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

// Panier récupéré depuis la base de données
$cartStmt = $conn->prepare("
    SELECT basket.*, products.name AS product_name, products.price AS product_price
    FROM basket
    JOIN products ON basket.product_id = products.id
    WHERE basket.user_id = ?
");
$cartStmt->bind_param("i", $userId);
$cartStmt->execute();
$cartResult = $cartStmt->get_result();
while ($cartItem = $cartResult->fetch_assoc()) {
    $cart[] = [
        'nom' => $cartItem['product_name'],
        'prix' => $cartItem['product_price'],
        'quantite' => $cartItem['quantity']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #fff0f5;
            margin: 0;
        }
        header {
            background: #e0529f;
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        .container {
            padding: 30px;
        }
        h2 {
            color: #d63384;
        }
        .info, .orders, .cart {
            background: #fff;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(220, 80, 150, 0.15);
        }
        .logout {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #d9534f;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
        }
        .logout:hover {
            background: #c9302c;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #f0c8dd;
        }
        th {
            background-color: #ffe0ec;
            color: #d63384;
        }
        .checkout {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #e0529f;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
        .checkout:hover {
            background-color: #c9307e;
        }
        .back-shop {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #d63384;
            font-weight: bold;
        }
    </style>
</head>
<body>

<header>
    <h1>Welcome, <?= $user['firstname'] ?> </h1>
    <a class="logout" href="logout.php">Logout</a>
</header>

<div class="container">

    <div class="info">
        <h2>👤 Your Info</h2>
        <p><strong>Name:</strong> <?= $user['firstname'] . " " . $user['lastname'] ?></p>
        <p><strong>Email:</strong> <?= $user['email'] ?></p>
    </div>

    <div class="orders">
        <h2>📦 Your Orders</h2>
        <?php if ($orders): ?>
    <table>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Date</th>
            <th>Payment ID</th>
        </tr>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= htmlspecialchars($order['product_name']) ?></td>
                <td><?= $order['quantity'] ?></td>
                <td><?= $order['total_price'] ?> MAD</td>
                <td><?= $order['order_date'] ?></td>
                <td><?= $order['id_payment'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>You haven't made any orders yet.</p>
<?php endif; ?>

    </div>

    <div class="cart">
        <h2>🛒 Your Cart</h2>
        <?php if (empty($cart)): ?>
            <p>Your cart is currently empty.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
                <?php foreach ($cart as $item): ?>
                    <tr>
                        <td><?= $item['nom'] ?></td>
                        <td><?= $item['quantite'] ?></td>
                        <td><?= $item['prix'] ?> DH</td>
                        <td><?= $item['quantite'] * $item['prix'] ?> DH</td>
                    </tr>
                    <?php $total += $item['quantite'] * $item['prix']; ?>
                <?php endforeach; ?>
            </table>
            <p><strong>Total:</strong> <?= $total ?> DH</p>
            <a href="paiment.php" class="checkout">Proceed to Payment</a>
        <?php endif; ?>
    </div>

    <a href="shop.php" class="back-shop">← Let's shop </a>

</div>

</body>
</html>
