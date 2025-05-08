<?php
include '../php/connexion.php';
session_start();


$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Vérifier si tous les champs sont remplis
    if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($password_confirm)) {
        $error = "❌ Tous les champs sont obligatoires.";
    } elseif ($password !== $password_confirm) {
        $error = "❌ Les mots de passe ne correspondent pas.";
    } else {
        // Vérifie si l'e-mail existe déjà
        $stmt = $conn->prepare("SELECT idu FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "❌ Cet e-mail est déjà utilisé.";
        } else {
            $stmt = $conn->prepare("INSERT INTO user (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $firstname, $lastname, $email, $password);

            if ($stmt->execute()) {
                $success = "✅ Utilisateur enregistré avec succès !";
            } else {
                $error = "Erreur d'insertion : " . $stmt->error;
            }
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Sign Up</title>
    <style>
        body {
            
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .signup-container {
            width: 400px;
            margin: 60px auto;
            padding: 30px;
            background-color: #fff0f5;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(255, 105, 180, 0.3);
        }

        h2 {
            text-align: center;
            color: #d63384;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #888;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 15px;
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

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #ff69b4;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #e055a3;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
        }

        .login-link a {
            color: #d63384;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .error, .success {
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
        }

        .error {
            background-color: #ffccd5;
            color: #c70039;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <form class="signup-container" method="POST" action="">
        <h2>Sign Up</h2>
        <p class="subtitle">Create your account</p>

        <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
        <?php if (!empty($success)) echo "<div class='success'>$success</div>"; ?>

        <div class="input-group">
            <label for="firstname">First name<span class="required">*</span></label>
            <input type="text" id="firstname" name="firstname" required>
        </div>

        <div class="input-group">
            <label for="lastname">Last name<span class="required">*</span></label>
            <input type="text" id="lastname" name="lastname" required>
        </div>

        <div class="input-group">
            <label for="email">Email<span class="required">*</span></label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="input-group">
            <label for="password">Password<span class="required">*</span></label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="input-group">
            <label for="password_confirm">Confirm Password<span class="required">*</span></label>
            <input type="password" id="password_confirm" name="password_confirm" required>
        </div>

        <button type="submit">Register</button>

        <p class="login-link">
            Already registered? <a href="login.php">Login here</a>
        </p>
    </form>
</body>
</html>
