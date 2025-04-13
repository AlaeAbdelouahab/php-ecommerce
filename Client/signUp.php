<?php
session_start();

$error = '';

// Lorsqu'on soumet le formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = htmlspecialchars($_POST['name']);
    $lastName = htmlspecialchars($_POST['last']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $confirmPassword = htmlspecialchars($_POST['confirm_password']);

    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "❌ Password and confirmation do not match.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        // Si tout est ok, on peut afficher les infos
        echo "<h2 style='text-align:center; color:#db2777;'>Registration Successful</h2>";
        echo "<div style='text-align:center; font-family:Arial;'>
                <p><strong>First Name:</strong> $firstName</p>
                <p><strong>Last Name:</strong> $lastName</p>
                <p><strong>Email:</strong> $email</p>
              </div>";
        session_destroy(); // Nettoyer la session
        exit;
    }
}

// Récupérer l'erreur si elle existe, et la supprimer juste après
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <style>
        :root {
            --pink-50: #fdf2f8;
            --pink-100: #fce7f3;
            --pink-200: #fbcfe8;
            --pink-300: #f9a8d4;
            --pink-400: #f472b6;
            --pink-500: #ec4899;
            --pink-600: #db2777;
            --pink-700: #be185d;
            --pink-800: #9d174d;
            --pink-900: #831843;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: white;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .signup-container {
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 2px solid var(--pink-400);
            border-radius: 15px;
            padding: 25px;
            width: 100%;
            max-width: 320px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .signup-container:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        h2 {
            color: var(--pink-600);
            margin-bottom: 10px;
            font-size: 1.8rem;
        }

        p.subtitle {
            color: var(--pink-400);
            margin-bottom: 20px;
            font-style: italic;
            font-size: 0.95rem;
        }

        .input-group {
            margin-bottom: 14px;
            text-align: left;
        }

        label {
            color: var(--pink-800);
            font-size: 0.95rem;
            margin-bottom: 4px;
            display: block;
        }

        input {
            width: 100%;
            padding: 9px;
            border: 1px solid var(--pink-200);
            border-radius: 5px;
            font-size: 0.95rem;
            background-color: var(--pink-50);
            color: #333;
        }

        input:focus {
            border-color: var(--pink-500);
            outline: none;
        }

        button {
            width: 100%;
            padding: 11px;
            background-color: var(--pink-500);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: var(--pink-600);
        }

        .login-link {
            margin-top: 15px;
            font-size: 0.9rem;
            color: var(--pink-700);
        }

        .login-link a {
            color: var(--pink-600);
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            margin-bottom: 15px;
            font-style: italic;
            font-size: 0.9rem;
        }

        @media (max-width: 500px) {
            .signup-container {
                padding: 20px;
                max-width: 90%;
            }
        }
    </style>
</head>
<body>
    <form class="signup-container" method="POST" action="">
        <h2>Sign Up</h2>
        <p class="subtitle">It's free and only takes a minute</p>

        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <div class="input-group">
            <label for="name">First Name:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="input-group">
            <label for="last">Last Name:</label>
            <input type="text" id="last" name="last" required>
        </div>

        <div class="input-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="input-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="input-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>

        <button type="submit">Register</button>

        <p class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </form>
</body>
</html>
