<?php
class Challenge {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create_challenge($titre, $description, $categorie, $deadline, $image, $id_user) {
        $sql = "INSERT INTO challenge (titre, description, categorie, deadline, image, id_user)
                VALUES (:titre, :description, :categorie, :deadline, :image, :id_user)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':titre'       => $titre,
            ':description' => $description,
            ':categorie'   => $categorie,
            ':deadline'    => $deadline,
            ':image'       => $image,
            ':id_user'     => $id_user  
        ]);
    }

    public function modifier_challenge($id_challenge, $titre, $description, $categorie, $deadline, $image, $id_user) {
        $sql = "UPDATE challenge
                SET titre = :titre, description = :description, categorie = :categorie,
                    deadline = :deadline, image = :image
                WHERE id_challenge = :id_challenge AND id_user = :id_user"; 
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':titre'        => $titre,
            ':description'  => $description,
            ':categorie'    => $categorie,
            ':deadline'     => $deadline,
            ':image'        => $image,
            ':id_challenge' => $id_challenge, 
            ':id_user'      => $id_user       
        ]);
    }

    public function supprimer_challenge($id_challenge, $id_user) {
        $sql = "DELETE FROM challenge 
                WHERE id_challenge = :id_challenge AND id_user = :id_user"; 
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_challenge' => $id_challenge,
            ':id_user'      => $id_user
        ]);
    }
    public function getChallengeByid($id_challenge) {
        $sql  = "SELECT * FROM challenge WHERE id_challenge = :id_challenge"; 
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_challenge' => $id_challenge]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAllChallenges() {
        $sql  = "SELECT * FROM challenge ORDER BY deadline ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function countParticipants($id_challenge) {
        $sql  = "SELECT COUNT(*) as nb FROM submissions WHERE id_challenge = :id_challenge"; 
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_challenge' => $id_challenge]);
        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['nb'];
    }
}