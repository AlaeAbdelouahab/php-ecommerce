<?php
session_start();
if (!isset($_SESSION['idu'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['idu'];
$conn = new mysqli("localhost", "root", "", "phpshop");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Supprimer un produit du panier
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM basket WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();

    header("Location: panier.php");
    exit;
}

// Récupérer les produits du panier
$cart = [];
$total = 0;
$stmt = $conn->prepare("
    SELECT basket.*, products.name AS product_name, products.price AS product_price
    FROM basket
    JOIN products ON basket.product_id = products.id
    WHERE basket.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cartResult = $stmt->get_result();

while ($cartItem = $cartResult->fetch_assoc()) {
    $cart[] = [
        'nom' => $cartItem['product_name'],
        'prix' => $cartItem['product_price'],
        'quantite' => $cartItem['quantity'],
        'product_id' => $cartItem['product_id']
    ];
}

// Calculer le total
foreach ($cart as $item) {
    $total += $item['quantite'] * $item['prix'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Cart</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fff0f5;
            margin: 0;
            padding: 30px;
        }
        h2::before {
            content: "🛒";
            margin-right: 10px;
            font-size: 2.5rem;
        }
        .cart {
            max-width: 800px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(255, 192, 203, 0.3);
        }
        .cart h2 {
            font-size: 28px;
            text-align: center;
            margin-bottom: 25px;
            color: #d63384;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background-color: #ffe0ec;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #d63384;
            border-bottom: 2px solid #f2c2d4;
        }
        td {
            padding: 15px;
            border-bottom: 1px solid #f9d6e3;
            color: #333;
            vertical-align: middle;
        }
        .total {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            color: #222;
            margin-top: 10px;
        }
        .checkout, .payment {
            display: block;
            width: 100%;
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }
        .checkout {
            background-color: #ff69b4;
            color: white;
        }
        .checkout:hover {
            background-color: #e0529f;
        }
        .payment {
            background-color: #32CD32;
            color: white;
        }
        .payment:hover {
            background-color: #28a745;
        }
        .back-link {
            display: inline-block;
            margin-top: 15px;
            color: #d63384;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .delete-button {
            color: red;
            font-weight: bold;
            text-decoration: none;
        }
        .delete-button:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="cart">
    <h2>My Shopping Cart</h2>
    <?php if (empty($cart)): ?>
        <p>Your cart is currently empty.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
                <th>Delete</th>
            </tr>
            <?php foreach ($cart as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nom']) ?></td>
                    <td><?= $item['quantite'] ?></td>
                    <td><?= $item['prix'] ?> DH</td>
                    <td><?= $item['quantite'] * $item['prix'] ?> DH</td>
                    <td>
                        <a href="panier.php?id=<?= $item['product_id'] ?>" class="delete-button">🗑️</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <div class="total">
            Total: <?= $total ?> DH
        </div>
        <a href="paiment.php" class="payment">Proceed to Payment</a>
    <?php endif; ?>

    <a href="Home.php" class="back-link">← Continue Shopping</a>
</div>
</body>
</html>
