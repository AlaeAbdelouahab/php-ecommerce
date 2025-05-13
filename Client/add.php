<?php
session_start();
if (!isset($_SESSION['idu'])) {
    echo "<script>
        alert('🛑 You must be logged in to add items to your cart!');
        window.location.href = 'login.php';
    </script>";
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $quantite = $_POST['quantite'];

    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    if (isset($_SESSION['panier'][$id])) {
        $_SESSION['panier'][$id]['quantite'] += $quantite;
    } else {
    
        $_SESSION['panier'][$id] = [
            'nom' => $nom,
            'prix' => $prix,
            'quantite' => $quantite
        ];
    }
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
