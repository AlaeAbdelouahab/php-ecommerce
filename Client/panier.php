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

// Modifier la quantité d'un produit
if (isset($_GET['action']) && isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];
    $action = $_GET['action'];

    // Récupérer la quantité actuelle
    $stmt = $conn->prepare("SELECT quantity FROM basket WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $currentQty = (int)$row['quantity'];

        if ($action === 'increase') {
            $newQty = $currentQty + 1;
        } elseif ($action === 'decrease') {
            $newQty = $currentQty - 1;
            if ($newQty < 1) {
                // Supprimer le produit si quantité < 1
                $stmtDel = $conn->prepare("DELETE FROM basket WHERE user_id = ? AND product_id = ?");
                $stmtDel->bind_param("ii", $user_id, $product_id);
                $stmtDel->execute();
                $stmtDel->close();
                header("Location: panier.php");
                exit;
            }
        } else {
            $newQty = $currentQty; // Pas de changement si action inconnue
        }

        // Mettre à jour la quantité si elle a changé
        if ($newQty !== $currentQty && $newQty >= 1) {
            $stmtUpdate = $conn->prepare("UPDATE basket SET quantity = ? WHERE user_id = ? AND product_id = ?");
            $stmtUpdate->bind_param("iii", $newQty, $user_id, $product_id);
            $stmtUpdate->execute();
            $stmtUpdate->close();
        }
    }
    $stmt->close();

    // Rediriger pour éviter le rechargement multiple
    header("Location: panier.php");
    exit;
}

// Supprimer un produit du panier
if (isset($_GET['delete_id'])) {
    $product_id = (int)$_GET['delete_id'];

    $stmt = $conn->prepare("DELETE FROM basket WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $stmt->close();

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

$stmt->close();

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
        a.quantity-control {
            font-weight: bold;
            text-decoration: none;
            padding: 5px 10px;
            background-color: #ff69b4;
            color: white;
            border-radius: 4px;
            user-select: none;
        }
        a.quantity-control:hover {
            background-color: #e0529f;
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
                    <td>
                        <a href="panier.php?action=decrease&id=<?= $item['product_id'] ?>" class="quantity-control">-</a>
                        <?= $item['quantite'] ?>
                        <a href="panier.php?action=increase&id=<?= $item['product_id'] ?>" class="quantity-control">+</a>
                    </td>
                    <td><?= $item['prix'] ?> DH</td>
                    <td><?= $item['quantite'] * $item['prix'] ?> DH</td>
                    <td>
                        <a href="panier.php?delete_id=<?= $item['product_id'] ?>" class="delete-button" onclick="return confirm('Are you sure you want to remove this product?');">🗑️</a>
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
