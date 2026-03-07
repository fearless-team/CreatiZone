<?php
// 1️⃣ Inclure la config et la classe Challenge
require_once "C:/wamp64/www/DS1/config/database.php"; // connexion PDO
require_once "C:/wamp64/www/DS1/app/models/challenge.php"; // classe Challenge

// 2️⃣ Instancier la classe Challenge
$challenge = new Challenge($pdo);

// 3️⃣ Créer un nouveau défi
echo "Création d'un défi...<br>";
$challenge->create_challenge(
    "Défi Test",
    "Description du défi test",
    "Graphisme",
    "2026-03-10",
    null,   // image optionnelle
    1       // user_id test
);
echo "Défi créé avec succès !<br>";

// 4️⃣ Récupérer tous les défis
echo "<br>Tous les défis :<br>";
$all = $challenge->getAllChallenges();
print_r($all);

// 5️⃣ Récupérer un défi par ID
echo "<br>Récupération du défi ID=1 :<br>";
$single = $challenge->getChallengeById(1);
print_r($single);

// 6️⃣ Modifier un défi
echo "<br>Modification du défi ID=1...<br>";
$challenge->modifier_challenge(
    1,                     // id
    "Défi Test Modifié",   // titre
    "Nouvelle description",// description
    "Design",              // catégorie
    "2026-03-15",          // deadline
    null,                  // image
    1                      // user_id
);
echo "Défi modifié !<br>";

// 7️⃣ Supprimer un défi
// echo "<br>Suppression du défi ID=1...<br>";
// $challenge->supprimer_challenge(1, 1);
// echo "Défi supprimé !<br>";
?>