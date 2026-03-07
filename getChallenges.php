<?php
session_start();
require_once "C:\wamp64\www\comments-app-php\comments-app\{classes,views,assets}\connexion.php"; // adjust path if needed

// Build query: challenges + author + votes
$sql = "
    SELECT 
        c.id_challenge AS id,
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
";

$stmt = $db->query($sql);
$challenges = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return JSON for frontend
header('Content-Type: application/json; charset=utf-8');
echo json_encode($challenges);
?>