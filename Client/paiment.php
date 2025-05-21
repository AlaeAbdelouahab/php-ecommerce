<?php
include '../php/connexion.php';
include '../php/auth.php';
$user_id = null;
if (isset($_SESSION['idu'])) {
    $user_id = $_SESSION['idu'];
} 
$successMessage = "";
$errorMessage= "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cardType = trim($_POST['card-type']);
    $cardNumber = trim($_POST['card-number']);
    $expiryDate = trim($_POST['expiry-date']);
    $cvv = trim($_POST['cvv']);
    if (empty($cardType) || empty($cardNumber) || empty($expiryDate) || empty($cvv)) {
        $errorMessage = "❌ All fields must be filled.";
    } else {
        try {
            $cart = [];
            $total = 0;
            $stmt = $conn->query("SELECT basket.*, products.name AS product_name, products.price AS product_price FROM basket JOIN products ON basket.product_id = products.id WHERE basket.user_id = $user_id");

            while ($row = $stmt->fetch_assoc()) {
                $cart[] = $row;
            }

            // Calculer le total
            foreach ($cart as $item) {
                $total += $item['quantity'] * $item['product_price'];
            }

            //create order
            $sql = "INSERT INTO orders(user_id, total_price) VALUES ($user_id, $total)";
            $stmt = $conn->query($sql);
            $order_id = $conn->insert_id;

            //order details
            foreach ($cart as $product) {
                $product_id = $product['product_id'];
                $color = $product['color'];
                $size = $product['size'];
                $quantity = $product['quantity'];
                
                $sql = "INSERT INTO order_details(user_id, product_id, color, size, quantity, order_id) VALUES ($user_id, $product_id, '$color', '$size', $quantity, $order_id)";
                $conn->query($sql);
            }
            $successMessage = "✅ Payment successful and also saved in orders.";
        } catch (Exception $e) {
            $errorMessage = "❌ Error: " . $e->getMessage();
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Page</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1f2937;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
           
        }

        .container {
            backdrop-filter: blur(12px);
            background-color: rgba(255, 255, 255, 0.2);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            color: #db2777;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #be185d;
            font-weight: bold;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 12px;
            border: 2px solid #f9a8d4;
            border-radius: 10px;
            background: #fff;
            font-size: 1rem;
        }

        input:focus,
        select:focus {
            border-color: #ec4899;
            outline: none;
        }

        button {
            width: 100%;
            padding: 15px;
            background-color: #ec4899;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #db2777;
        }

        .message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 30px;
            border-radius: 15px;
            font-size: 1.2rem;
            text-align: center;
            display: block;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            z-index: 9999;
        }

        .success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .error {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Payment Details</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="card-type">Card Type</label>
            <select id="card-type" name="card-type">
                <option value="">Select a card</option>
                <option value="visa" <?= (isset($cardType) && $cardType == 'visa') ? 'selected' : '' ?>>Visa</option>
                <option value="mastercard" <?= (isset($cardType) && $cardType == 'mastercard') ? 'selected' : '' ?>>MasterCard</option>
                <option value="amex" <?= (isset($cardType) && $cardType == 'amex') ? 'selected' : '' ?>>American Express</option>
                <option value="paypal" <?= (isset($cardType) && $cardType == 'paypal') ? 'selected' : '' ?>>PayPal</option>
            </select>
        </div>

        <div class="form-group">
            <label for="card-number">Card Number</label>
            <input type="text" id="card-number" name="card-number" placeholder="1234 5678 9012 3456" value="<?= htmlspecialchars($cardNumber ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="expiry-date">Expiry Date</label>
            <input type="text" id="expiry-date" name="expiry-date" placeholder="MM/YY" value="<?= htmlspecialchars($expiryDate ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="cvv">CVV</label>
            <input type="text" id="cvv" name="cvv" placeholder="123" value="<?= htmlspecialchars($cvv ?? '') ?>">
        </div>

        <button type="submit">Pay Now</button>
    </form>

    <a href="Home.php" style="display:block; margin-top: 20px; text-align:center; color: #e0529f;">← Continue Shopping</a>
</div>

<?php if ($successMessage): ?>
    <div class="message success"><?= $successMessage ?></div>
<?php elseif ($errorMessage): ?>
    <div class="message error"><?= $errorMessage ?></div>
<?php endif; ?>

<script>
  // Cacher automatiquement le message après 30 secondes
  const successMessage = document.querySelector('.message.success');
  const errorMessage = document.querySelector('.message.error');

  function hideAfterTimeout(element, timeout = 3000) {
    if (element) {
      setTimeout(() => {
        element.style.display = 'none';
      }, timeout);
    }
  }

  hideAfterTimeout(successMessage);
  hideAfterTimeout(errorMessage);
</script>

</body>
</html>
