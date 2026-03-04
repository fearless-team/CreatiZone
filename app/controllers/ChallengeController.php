<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "C:/wamp64/www/DS1/config/database.php";
require_once "C:/wamp64/www/DS1/app/models/Challenge.php";

$challenge = new Challenge($pdo);

// Utilisateur connecté
$_SESSION['user_id'] = (int)($_SESSION['user_id'] ?? 1);

// Catégories fixes
$categories = ['Sport', 'Santé', 'Culture', 'Technologie', 'Finance', 'Autre'];

// Dossier upload
define('UPLOAD_DIR', 'C:/wamp64/www/DS1/uploads/');

// ── SUPPRESSION ──────────────────────────────────────────────
if (isset($_GET['supprimer'])) {
    $id      = (int)$_GET['supprimer'];
    $user_id = $_SESSION['user_id'];

    $defi = $challenge->getChallengeByid($id);
    if ($defi && !empty($defi['image'])) {
        $fichier = UPLOAD_DIR . $defi['image'];
        if (file_exists($fichier)) unlink($fichier);
    }

    $challenge->supprimer_challenge($id, $user_id);
    header("Location: challengeController.php");
    exit;
}

// ── CHARGER LE DÉFI À MODIFIER ────────────────────────────────
$defi_a_modifier = null;
if (isset($_GET['modifier'])) {
    $id              = (int)$_GET['modifier'];
    $defi_a_modifier = $challenge->getChallengeByid($id);
}

// ── FONCTION UPLOAD IMAGE ─────────────────────────────────────
function traiter_image(): ?string {
    if (empty($_FILES['image']['name'])) return null;

    $fichier    = $_FILES['image'];
    $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext        = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
    $taille_max = 2 * 1024 * 1024;

    if (!in_array($ext, $extensions))      return 'ERREUR:Format non autorisé (JPG, PNG, GIF, WEBP).';
    if ($fichier['size'] > $taille_max)    return 'ERREUR:Image trop lourde (max 2 Mo).';
    if ($fichier['error'] !== UPLOAD_ERR_OK) return 'ERREUR:Erreur lors de l\'upload.';

    $nom_fichier = uniqid('img_', true) . '.' . $ext;
    $destination = UPLOAD_DIR . $nom_fichier;

    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
    if (!move_uploaded_file($fichier['tmp_name'], $destination)) return 'ERREUR:Impossible de sauvegarder l\'image.';

    return $nom_fichier;
}

// ── SOUMISSION FORMULAIRE ─────────────────────────────────────
$message = '';
if (isset($_POST['submit'])) {
    $titre       = trim($_POST['titre']       ?? '');
    $description = trim($_POST['description'] ?? '');
    $categorie   = trim($_POST['categorie']   ?? '');
    $deadline    = $_POST['deadline']         ?? '';
    $user_id     = $_SESSION['user_id'];

    // Validation basique
    if (empty($titre) || empty($description) || empty($categorie) || empty($deadline)) {
        $message = "<div class='alert-err'><i class='bi bi-exclamation-circle-fill'></i> Veuillez remplir tous les champs.</div>";
    } else {
        $resultat_image = traiter_image();

        if ($resultat_image !== null && str_starts_with($resultat_image, 'ERREUR:')) {
            $message = "<div class='alert-err'><i class='bi bi-exclamation-circle-fill'></i> " . htmlspecialchars(substr($resultat_image, 7)) . "</div>";
        } else {
            if (!empty($_POST['id'])) {
                // MODIFICATION
                $id    = (int)$_POST['id'];
                $image = $resultat_image ?? ($_POST['ancienne_image'] ?? null);

                // Supprimer ancienne image si remplacée
                if ($resultat_image && !empty($_POST['ancienne_image'])) {
                    $ancien = UPLOAD_DIR . $_POST['ancienne_image'];
                    if (file_exists($ancien)) unlink($ancien);
                }

                $challenge->modifier_challenge($id, $titre, $description, $categorie, $deadline, $image, $user_id);
                $message         = "<div class='alert-ok'><i class='bi bi-check-circle-fill'></i> Défi modifié avec succès !</div>";
                $defi_a_modifier = null;

            } else {
                // CRÉATION
                $image = $resultat_image;
                $challenge->create_challenge($titre, $description, $categorie, $deadline, $image, $user_id);
                $message = "<div class='alert-ok'><i class='bi bi-check-circle-fill'></i> Défi créé avec succès !</div>";
            }
        }
    }
}

// ── RÉCUPÉRER TOUS LES DÉFIS ──────────────────────────────────
$defis = $challenge->getAllChallenges();

// ── CHARGER LA VUE ────────────────────────────────────────────
require_once "C:/wamp64/www/DS1/app/views/defis_form.php";