<?php
session_start();

// Utilisateur fictif enregistré (dans un vrai projet, utilisez une base de données)
$users = [
    "user@example.com" => "123456"
];

$error = '';

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);

    if (isset($users[$email]) && $users[$email] === $password) {
        // Connexion réussie
        header("Location: dashboard.html");
        exit;
    } else {
        $error = "❌ Email or password incorrect. Please sign up first.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sign in</title>
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
            font-family: 'Arial', sans-serif;
            background: url('picS613.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #32022c;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 2px solid var(--pink-400);
            border-radius: 15px;
            padding: 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-container:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        h2 {
            color: var(--pink-600);
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 1.1rem;
            margin-bottom: 8px;
            color: var(--pink-800);
        }

        input[type="email"], input[type="password"] {
            padding: 10px;
            border: 1px solid var(--pink-200);
            border-radius: 5px;
            font-size: 1rem;
            background-color: var(--pink-50);
            color: #333;
        }

        input[type="email"]:focus, input[type="password"]:focus {
            border-color: var(--pink-500);
            outline: none;
        }

        button {
            width: 100%;
            padding: 15px;
            background-color: var(--pink-500);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: var(--pink-600);
        }

        .signup-link {
            margin-top: 20px;
            font-size: 1rem;
            color: var(--pink-600);
        }

        .signup-link a {
            color: var(--pink-500);
            text-decoration: none;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        @media (max-width: 600px) {
            .login-container {
                width: 90%;
            }

            h2 {
                font-size: 1.5rem;
            }

            button {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <form action="" method="POST">
            <div class="input-group">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>

            <div class="input-group">
                <label for="password">Password :</label>
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>

            <button type="submit">Login</button>
        </form>

        <p class="signup-link">Don't have an account? <a href="signUp.php">Create new account</a></p>
    </div>
</body>
</html>
