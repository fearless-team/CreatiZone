<?php
class CommentController {
    private int $submissionId;
    private PDO $db;

    public function __construct(int $submissionId) {
        $this->submissionId = $submissionId;
        // Assuming $db is globally available from connexion.php
        global $db;
        $this->db = $db;
    }

    public function handle(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        $action = $_POST['action'] ?? null;

        switch ($action) {
            case 'add':
                $this->addComment();
                break;
            case 'delete':
                $this->deleteComment();
                break;
            case 'like':
                $this->likeComment();
                break;
        }
    }

    private function addComment(): void {
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Vous devez être connecté']);
            exit;
        }

        $id_user       = $_SESSION['user']['id_user'];
        $id_submission = filter_input(INPUT_POST, 'submission_id', FILTER_VALIDATE_INT);
        $content       = trim($_POST['content'] ?? '');

        if (!$id_submission || $content === '') {
            echo json_encode(['success' => false, 'message' => 'Commentaire invalide']);
            exit;
        }

        $sql = "INSERT INTO comments (id_submission, id_user, content) 
                VALUES (:id_submission, :id_user, :content)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_submission' => $id_submission,
            ':id_user'       => $id_user,
            ':content'       => $content
        ]);

        $commentId = $this->db->lastInsertId();

        $author = $_SESSION['user']['prenom'] . ' ' . $_SESSION['user']['nom'];
        $initials = strtoupper($_SESSION['user']['prenom'][0] . $_SESSION['user']['nom'][0]);

        $comment = [
            'id'       => $commentId,
            'author'   => $author,
            'content'  => htmlspecialchars($content),
            'date'     => date('Y-m-d H:i:s'),
            'likes'    => 0,
            'liked'    => false,
            'initials' => $initials,
            'color'    => '#3498db'
        ];

        echo json_encode(['success' => true, 'comment' => $comment]);
        exit;
    }

    private function deleteComment(): void {
        session_start();
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Non autorisé']);
            exit;
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID invalide']);
            exit;
        }

        // In production: check if user is author or admin
        $sql = "DELETE FROM comments WHERE id_comment = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        echo json_encode(['success' => true]);
        exit;
    }

    private function likeComment(): void {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID invalide']);
            exit;
        }

        // Example: toggle like count (simplified)
        $sql = "UPDATE comments SET likes = likes + 1 WHERE id_comment = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        // Fetch updated likes
        $likes = $this->db->query("SELECT likes FROM comments WHERE id_comment = $id")->fetchColumn();

        echo json_encode(['success' => true, 'liked' => true, 'likes' => $likes]);
        exit;
    }

    public function getComments(): array {
        $sql = "SELECT c.id_comment, c.content, c.created_at,
                       u.nom, u.prenom
                FROM comments c
                JOIN user u ON c.id_user = u.id_user
                WHERE c.id_submission = :submissionId
                ORDER BY c.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':submissionId' => $this->submissionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}