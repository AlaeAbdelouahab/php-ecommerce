<?php
session_start();
$prenom = isset($_SESSION['idu']) ? $_SESSION['prenom'] ?? 'Sweet Girl' : 'Sweet Girl';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - Sweet Girls Fashion</title>
    <style>
        :root {
             --rose:rgb(228, 160, 176);
            --rose-dark:rgb(197, 121, 141);
             --rose-light: #fce7ec;
            --gris: #f3f4f6;
            --text: #555;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--gris);
            color: var(--text);
        }

        header {
            background-color: var(--rose);
            color: white;
            padding: 25px 0;
            text-align: center;
        }

        nav {
            background-color: var(--rose-light);
            padding: 10px 20px;
            text-align: center;
        }

        nav a {
            text-decoration: none;
            color: var(--rose-dark);
            margin: 0 15px;
            font-weight: bold;
            font-size: 16px;
        }

        .content {
            padding: 30px 60px;
        }

        .content h2 {
            color: var(--rose-dark);
            font-size: 28px;
            margin-top: 40px;
        }

        .mission-box {
            background-color: var(--rose-light);
            padding: 20px;
            margin: 20px 0;
            border-left: 6px solid var(--rose-dark);
            border-radius: 8px;
        }

        ul {
            margin-left: 25px;
        }

        footer {
            text-align: center;
            padding: 15px;
            background-color: var(--rose);
            color: white;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<header>
    <h1>About Us</h1>
    <p>Welcome <?= $prenom ?> to our lovely fashion world! 🌸</p>
</header>

<nav>
    <a href="Home.php">Home</a>
    <a href="shop.php">Shop</a>
    <a href="#">Contact</a>
</nav>

<div class="content">
    <h2>Who Are We ?</h2>
    <p>We are a passionate team dedicated to bringing the latest trends and cutest outfits for young girls and little princesses 👗.</p>

    <div class="mission-box">
        <h3>🎯 Our Mission</h3>
        <p>To empower every girl to feel beautiful, confident and stylish at every age.</p>
    </div>

    <h2>Our Collection</h2>
    <p>We carefully select fabrics and designs that are trendy, comfy, and full of joy. From school looks to birthday dresses – we’ve got it all .</p>

    <h2>Why Choose Us?</h2>
    <ul>
        <li>🌸 Unique & trendy styles</li>
        <li>🎁 Affordable prices</li>
        <li>🚚 Fast delivery across Morocco</li>
        <li>💌 Personalized customer service</li>
    </ul>
</div>

<footer>
    &copy; 2025 Sweet Girls Fashion. All rights reserved.
</footer>

</body>
</html>
