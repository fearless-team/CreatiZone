<?php
$host = 'localhost';
$dbname = 'challenge_hub';
$user = 'root';         // votre user MySQL
$pass = '';             // votre mot de passe

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['error' => $e->getMessage()]));
}
$stmt = $pdo->query("
    SELECT c.id_challenge AS id,
           c.titre AS title,
           c.description,
           c.categorie AS category,
           c.deadline,
           u.nom AS author,
           COALESCE(AVG(v.note), 0) AS noteMoyenne,
           COUNT(v.id) AS popularity
    FROM challenge c
    JOIN user u ON c.id_user = u.id_user
    LEFT JOIN submissions s ON s.id_challenge = c.id_challenge
    LEFT JOIN votes v ON v.id_submission = s.id_submission
    GROUP BY c.id_challenge
    ORDER BY c.date_creation DESC
");

$challenges = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($challenges);

?>
