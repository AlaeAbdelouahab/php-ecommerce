<?php 
try {
    $pdo = new PDO("mysql:host=localhost;dbname=phpshop", "root", "");
} catch (PDOException $e) {
    echo "La connexion à la base de données a échoué: " . $e->getMessage();
}
?>
