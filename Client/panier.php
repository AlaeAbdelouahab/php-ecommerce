<?php
session_start();

$cart = $_SESSION['panier'] ?? [];
$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Cart</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fff0f5; /* light pink */
            margin: 0;
            padding: 30px;
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

        /* Styling the buttons */
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

        /* Checkout Button */
        .checkout {
            background-color: #ff69b4;
            color: white;
        }

        .checkout:hover {
            background-color: #e0529f;
        }

        /* Payment Button */
        .payment {
            background-color: #32CD32; /* green color */
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
            </tr>
            <?php foreach ($cart as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nom']) ?></td>
                    <td><?= $item['quantite'] ?></td>
                    <td><?= $item['prix'] ?> DH</td>
                    <td><?= $item['quantite'] * $item['prix'] ?> DH</td>
                </tr>
                <?php $total += $item['quantite'] * $item['prix']; ?>
            <?php endforeach; ?>
        </table>

        <div class="total">
            Total: <?= $total ?> DH
        </div>

        <!-- Checkout Button -->
        <a href="#" class="checkout">CHECKOUT</a>

        <!-- Proceed to Payment Button -->
        <a href="payment.php" class="payment">Proceed to Payment</a> <!-- Button now redirects to payment.php -->

    <?php endif; ?>

    <a href="index.php" class="back-link">← Continue Shopping</a>
</div>

</body>
</html>
