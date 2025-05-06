<?php
$successMessage = '';
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cardType = $_POST['card-type'];
    $cardNumber = $_POST['card-number'];
    $expiryDate = $_POST['expiry-date'];
    $cvv = $_POST['cvv'];

    if (empty($cardType) || empty($cardNumber) || empty($expiryDate) || empty($cvv)) {
        $errorMessage = "❌ All fields must be filled.";
    } else {
        $successMessage = "✅ Payment was successful.";
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
            background: #fdf2f8;
            font-size: 1rem;
            transition: border-color 0.3s;
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
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #db2777;
        }

        .popup-message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 10px;
            font-size: 1.1rem;
            display: none;
            text-align: center;
        }

        .popup-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .popup-error {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Payment Details</h2>
    <form method="POST" action="" id="paymentForm">
        <div class="form-group">
            <label for="card-type">Card Type</label>
            <select id="card-type" name="card-type">
                <option value="">Select a card</option>
                <option value="visa">Visa</option>
                <option value="mastercard">MasterCard</option>
                <option value="amex">American Express</option>
                <option value="paypal">PayPal</option>
            </select>
        </div>

        <div class="form-group">
            <label for="card-number">Card Number</label>
            <input type="text" id="card-number" name="card-number" placeholder="1234 5678 9012 3456">
        </div>

        <div class="form-group">
            <label for="expiry-date">Expiry Date</label>
            <input type="text" id="expiry-date" name="expiry-date" placeholder="MM/YY">
        </div>

        <div class="form-group">
            <label for="cvv">CVV</label>
            <input type="text" id="cvv" name="cvv" placeholder="123">
        </div>

        <button type="submit" id="payButton">Pay Now</button>
    </form>

    <div id="popupMessage" class="popup-message"></div>
    <a href="Home.php" class="back-link" style="color: #e0529f;">← Continue Shopping</a>
</div>

<script>
    document.getElementById('paymentForm').addEventListener('submit', function (event) {
        var cardType = document.getElementById('card-type').value.trim();
        var cardNumber = document.getElementById('card-number').value.trim();
        var expiryDate = document.getElementById('expiry-date').value.trim();
        var cvv = document.getElementById('cvv').value.trim();
        var popup = document.getElementById('popupMessage');

        popup.style.display = "none";

        if (!cardType || !cardNumber || !expiryDate || !cvv) {
            event.preventDefault();
            popup.className = 'popup-message popup-error';
            popup.innerText = "❌ All fields must be filled.";
            popup.style.display = "block";

            setTimeout(function () {
                popup.style.display = "none";
            }, 20000);
        } else {
            popup.className = 'popup-message popup-success';
            popup.innerText = "✅ Payment was successful.";
            popup.style.display = "block";

            setTimeout(function () {
                popup.style.display = "none";
            }, 20000); 
        }
    });
</script>
</body>
</html>
