<?php 

$conn = new mysqli("localhost", "root", "", "phpshop");
if ($conn->connect_error) {
    echo "La connexion à la base de données a échoué: " . $e->getMessage();
}
?>
