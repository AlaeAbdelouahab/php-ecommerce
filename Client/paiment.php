<?php
session_start();
include '../php/connexion.php';
$successMessage = "";
$errorMessage= "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cardType = trim($_POST['card-type']);
    $cardNumber = trim($_POST['card-number']);
    $expiryDate = trim($_POST['expiry-date']);
    $cvv = trim($_POST['cvv']);

    $userId = $_SESSION['user_id'] ?? 1; 
    if (empty($cardType) || empty($cardNumber) || empty($expiryDate) || empty($cvv)) {
        $errorMessage = "❌ All fields must be filled.";
    } else {
        $conn->begin_transaction();
        try {
            // 1. Insérer dans payments
            $stmt = $conn->prepare("INSERT INTO payments (user_id, card_type, card_number, expiry_date, cvv) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $userId, $cardType, $cardNumber, $expiryDate, $cvv);
            $stmt->execute();
            $paymentId = $stmt->insert_id;
            $stmt->close();

            // 2. Insérer dans orders (avec des valeurs d'exemple à adapter)
            $productId = 1; // Remplace par une valeur logique (ex : produit par défaut)
            $quantity = 1; // Valeur par défaut
            $totalPrice = 0.00; 
            $stmt2 = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity, total_price, order_date, id_payment) VALUES (?, ?, ?, ?, NOW(), ?)");
            $stmt2->bind_param("iiidi", $userId, $productId, $quantity, $totalPrice, $paymentId);
            $stmt2->execute();
            $stmt2->close();

            $conn->commit();
            $successMessage = "✅ Payment successful and also saved in orders.";
        } catch (Exception $e) {
            $conn->rollback();
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
