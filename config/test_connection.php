<?php
$host = 'localhost';
$dbname = 'challengehub';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion PDO réussie !";
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}