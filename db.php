<?php
$host = "localhost";   // ou 127.0.0.1
$dbname = "biblio_db";
$username = "root";    // adapte selon ton installation
$password = "";        // adapte si tu as défini un mot de passe

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
