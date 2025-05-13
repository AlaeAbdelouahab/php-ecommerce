<?php
session_start();
include '../php/connexion.php';
include '../php/auth.php';
$cart = $_SESSION['panier'] ?? [];
$user_id = $_SESSION['idu'] ?? null;

if ($user_id && !empty($cart)) {
    foreach ($cart as $product_id => $item) {
        $quantite = $item['quantite'];

        // Vérifier si ce produit est déjà enregistré pour ce user
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);

        if ($stmt->rowCount() > 0) {
            // Mise à jour de la quantité
            $pdo->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?")
                ->execute([$quantite, $user_id, $product_id]);
        } else {
            // Insertion d'une nouvelle ligne
            $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)")
                ->execute([$user_id, $product_id, $quantite]);
        }
    }
}

session_destroy();
header("Location: login.php");
exit;
?>
