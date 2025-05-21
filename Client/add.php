<?php
session_start();
include '../php/connexion.php';

if (!isset($_SESSION['idu'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['idu'];
$product_id = $_POST['id'];
$nom = $_POST['nom'];
$prix = $_POST['prix'];
$quantite = $_POST['quantite'];

// Vérifier si le produit est déjà dans le panier
$check = $conn->prepare("SELECT quantity FROM basket WHERE user_id = ? AND product_id = ?");
$check->bind_param("ii", $user_id, $product_id);
$check->execute();
$check_result = $check->get_result();

if ($check_result->num_rows > 0) {
    // Produit déjà dans le panier, incrémenter la quantité
    $row = $check_result->fetch_assoc();
    $new_quantity = $row['quantity'] + $quantite;

    $update = $conn->prepare("UPDATE basket SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $update->bind_param("iii", $new_quantity, $user_id, $product_id);
    $update->execute();
} else {
    // Produit non présent, l’ajouter
    $insert = $conn->prepare("INSERT INTO basket (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $insert->bind_param("iii", $user_id, $product_id, $quantite);
    $insert->execute();
}

header("Location: shop.php");
exit();
?>
