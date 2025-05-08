<?php
include '../php/connexion.php';
session_start();

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);

    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if ($user['password'] === $password) {
            $_SESSION['idu'] = $user['idu']; 
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "❌ Incorrect password.";
        }
    } else {
        $error = "❌ Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        :root {
            --pink-500: #ec4899;
            --pink-600: #db2777;
            --pink-800: #9d174d;
            --light-pink: #ff69b4;
            --lighter-pink: #fbcfe8;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            width: 400px;
            padding: 30px;
            background-color: #fff0f5;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(255, 105, 180, 0.3);
            text-align: center;
        }

        h2 {
            color: var(--pink-600);
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #555;
        }

        label .required {
            color: red;
            margin-left: 4px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #f3c2d3;
            border-radius: 10px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: var(--pink-500);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: var(--pink-600);
        }

        .error {
            background-color: #ffccd5;
            color: #c70039;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
        }

        .login-link a {
            color: var(--pink-600);
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="input-group">
                <label for="email">Email <span style="color:red">*</span></label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Password <span style="color:red">*</span></label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
            <p class="login-link">Don't have an account? <a href="signUp.php">Sign up here</a></p>
            <a href="Home.php" class="back-link" style="color: #e0529f;">← Continue Shopping</a>

        </form>
    </div>
</body>
</html>
