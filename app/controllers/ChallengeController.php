<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "C:/wamp64/www/DS1/config/database.php";
require_once "C:/wamp64/www/DS1/app/models/Challenge.php";

$challenge = new Challenge($pdo);

// ── Session utilisateur 
$_SESSION['id_user'] = (int)($_SESSION['id_user'] ?? 1);
$id_user = $_SESSION['id_user'];

// ── CSRF simple 
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = md5(uniqid(rand(), true));
}

// ── Catégories dispo 
$categories = ['Sport', 'Santé', 'Culture', 'Technologie', 'Finance', 'Autre'];

define('UPLOAD_DIR', 'C:/wamp64/www/DS1/uploads/');

// ── SUPPRESSION 
if (isset($_GET['supprimer'])) {

    // Vérifier CSRF
    if (!isset($_GET['token']) || $_GET['token'] !== $_SESSION['csrf_token']) {
        die(" Action non autorisée !");
    }

    $id_challenge = (int)$_GET['supprimer'];
    $defi = $challenge->getChallengeByid($id_challenge);
    if ($defi && !empty($defi['image'])) {
        $fichier = UPLOAD_DIR . $defi['image'];
        if (file_exists($fichier)) unlink($fichier);
    }

    $challenge->supprimer_challenge($id_challenge, $id_user);
    header("Location: challengeController.php");
    exit;
}

// ── CHARGER LE DÉFI À MODIFIER
$defi_a_modifier = null;
if (isset($_GET['modifier'])) {
    $id_challenge    = (int)$_GET['modifier'];
    $defi_a_modifier = $challenge->getChallengeByid($id_challenge);
}

// ── FONCTION UPLOAD IMAGE 
function traiter_image(): ?string {
    if (empty($_FILES['image']['name'])) return null;

    $fichier    = $_FILES['image'];
    $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext        = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
    $taille_max = 2 * 1024 * 1024;

    if (!in_array($ext, $extensions))        return 'ERREUR:Format non autorisé (JPG, PNG, GIF, WEBP).';
    if ($fichier['size'] > $taille_max)      return 'ERREUR:Image trop lourde (max 2 Mo).';
    if ($fichier['error'] !== UPLOAD_ERR_OK) return 'ERREUR:Erreur lors de l\'upload.';

    // Vérifier que c'est vraiment une image
    $infos = getimagesize($fichier['tmp_name']);
    if ($infos === false) return 'ERREUR:Le fichier n\'est pas une image valide.';

    $nom_fichier = uniqid('img_', true) . '.' . $ext;
    $destination = UPLOAD_DIR . $nom_fichier;

    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
    if (!move_uploaded_file($fichier['tmp_name'], $destination)) return 'ERREUR:Impossible de sauvegarder l\'image.';

    return $nom_fichier;
}

// ── SOUMISSION FORMULAIRE
$message = '';
if (isset($_POST['submit'])) {

    // Vérifier CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die(" Action non autorisée !");
    }

    $titre       = trim($_POST['titre']       ?? '');
    $description = trim($_POST['description'] ?? '');
    $categorie   = trim($_POST['categorie']   ?? '');
    $deadline    = $_POST['deadline']         ?? '';

    // Valider catégorie
    if (!in_array($categorie, $categories)) {
        $categorie = '';
    }

    if (empty($titre) || empty($description) || empty($categorie) || empty($deadline)) {
        $message = "<div class='alert-err'><i class='bi bi-exclamation-circle-fill'></i> Veuillez remplir tous les champs.</div>";
    } else {
        $resultat_image = traiter_image();

        if ($resultat_image !== null && str_starts_with($resultat_image, 'ERREUR:')) {
            $message = "<div class='alert-err'><i class='bi bi-exclamation-circle-fill'></i> " . htmlspecialchars(substr($resultat_image, 7)) . "</div>";
        } else {
            if (!empty($_POST['id_challenge'])) {
                // MODIFICATION
                $id_challenge = (int)$_POST['id_challenge'];
                $image        = $resultat_image ?? ($_POST['ancienne_image'] ?? null);

                if ($resultat_image && !empty($_POST['ancienne_image'])) {
                    $ancien = UPLOAD_DIR . basename($_POST['ancienne_image']);
                    if (file_exists($ancien)) unlink($ancien);
                }

                $challenge->modifier_challenge($id_challenge, $titre, $description, $categorie, $deadline, $image, $id_user);
                $message         = "<div class='alert-ok'><i class='bi bi-check-circle-fill'></i> Défi modifié avec succès !</div>";
                $defi_a_modifier = null;
            } else {
                // CRÉATION
                $challenge->create_challenge($titre, $description, $categorie, $deadline, $resultat_image, $id_user);
                $message = "<div class='alert-ok'><i class='bi bi-check-circle-fill'></i> Défi créé avec succès !</div>";
            }
        }
    }
}

// ── RÉCUPÉRER TOUS LES DÉFIS + PARTICIP
$defis = $challenge->getAllChallenges();
foreach ($defis as &$defi) {
    $defi['nb_participants'] = $challenge->countParticipants($defi['id_challenge']);
}
unset($defi);

// ── CHARGER LA VUE 
require_once "C:/wamp64/www/DS1/app/views/defis_form.php";