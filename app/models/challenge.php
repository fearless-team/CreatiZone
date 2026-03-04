<?php
class Challenge {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create_challenge($titre, $description, $categorie, $deadline, $image, $user_id) {
        $sql = "INSERT INTO challenge (titre, description, categorie, deadline, image, user_id)
                VALUES (:titre, :description, :categorie, :deadline, :image, :user_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':titre'       => $titre,
            ':description' => $description,
            ':categorie'   => $categorie,
            ':deadline'    => $deadline,
            ':image'       => $image,
            ':user_id'     => $user_id
        ]);
    }

    public function modifier_challenge($id, $titre, $description, $categorie, $deadline, $image, $user_id) {
        $sql = "UPDATE challenge
                SET titre = :titre, description = :description, categorie = :categorie,
                    deadline = :deadline, image = :image
                WHERE id = :id AND user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':titre'       => $titre,
            ':description' => $description,
            ':categorie'   => $categorie,
            ':deadline'    => $deadline,
            ':image'       => $image,
            ':id'          => $id,
            ':user_id'     => $user_id
        ]);
    }

    public function supprimer_challenge($id, $user_id) {
        $sql = "DELETE FROM challenge WHERE id = :id AND user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id, ':user_id' => $user_id]);
    }

    public function getChallengeByid($id) {
        $sql  = "SELECT * FROM challenge WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllChallenges() {
        $sql  = "SELECT * FROM challenge ORDER BY deadline ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── Compter les participants d'un défi ──────────────────
    public function countParticipants($challenge_id) {
        $sql  = "SELECT COUNT(*) as nb FROM participation WHERE challenge_id = :challenge_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':challenge_id' => $challenge_id]);
        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['nb'];
    }
}