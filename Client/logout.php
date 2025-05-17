<?php
session_start();
include '../php/connexion.php';
$cart = $_SESSION['panier'] ?? [];
$user_id = $_SESSION['idu'] ?? null;

if ($user_id && !empty($cart)) {
    foreach ($cart as $product_id => $item) {
        $quantite = $item['quantite'];

        // Vérifier si ce produit est déjà enregistré pour ce user
        $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
$stmt->store_result(); // Nécessaire avant num_rows
if ($stmt->num_rows > 0) {
   // Mise à jour de la quantité
            $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?")
                ->execute([$quantite, $user_id, $product_id]);
        } else {
            // Insertion d'une nouvelle ligne
            $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)")
                ->execute([$user_id, $product_id, $quantite]);
        }
    }
}

session_destroy();
header("Location: login.php");
exit;
?>
