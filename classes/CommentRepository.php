<?php

/**
 * CommentRepository
 * Gère la persistance des commentaires en session (simule une BDD).
 */
class CommentRepository
{
    private const SESSION_KEY = 'comments_data';
    private static int $nextId = 100;

    public static function init(): void
    {
        if (!isset($_SESSION[self::SESSION_KEY])) {
            // Données de démonstration
            $_SESSION[self::SESSION_KEY] = self::seedData();
        }
    }

    /* ── CRUD ── */

    public static function findBySubmission(int $submissionId): array
    {
        $rows     = $_SESSION[self::SESSION_KEY] ?? [];
        $comments = [];

        foreach ($rows as $row) {
            if ($row['submission_id'] === $submissionId) {
                $comments[] = self::hydrate($row);
            }
        }

        usort($comments, fn($a, $b) => $b->getId() - $a->getId());
        return $comments;
    }

    public static function find(int $id): ?Comment
    {
        foreach ($_SESSION[self::SESSION_KEY] ?? [] as $row) {
            if ($row['id'] === $id) return self::hydrate($row);
        }
        return null;
    }

    public static function save(Comment $comment): Comment
    {
        $rows  = $_SESSION[self::SESSION_KEY] ?? [];
        $newId = ++self::$nextId + time() % 1000; // id unique

        $rows[] = [
            'id'            => $newId,
            'author'        => $comment->getAuthor(),
            'role'          => $comment->getRole(),
            'content'       => $comment->getContent(),
            'submission_id' => $comment->getSubmissionId(),
            'likes'         => $comment->getLikes(),
            'liked_by_me'   => $comment->isLikedByMe(),
            'date'          => (new DateTime())->format('Y-m-d H:i:s'),
        ];

        $_SESSION[self::SESSION_KEY] = $rows;
        return self::hydrate(end($rows));
    }

    public static function delete(int $id): bool
    {
        $rows = $_SESSION[self::SESSION_KEY] ?? [];
        foreach ($rows as $key => $row) {
            if ($row['id'] === $id) {
                unset($rows[$key]);
                $_SESSION[self::SESSION_KEY] = array_values($rows);
                return true;
            }
        }
        return false;
    }

    public static function toggleLike(int $id): ?array
    {
        $rows = &$_SESSION[self::SESSION_KEY];
        foreach ($rows as &$row) {
            if ($row['id'] === $id) {
                $row['liked_by_me'] = !$row['liked_by_me'];
                $row['likes']       += $row['liked_by_me'] ? 1 : -1;
                return ['likes' => $row['likes'], 'liked' => $row['liked_by_me']];
            }
        }
        return null;
    }

    /* ── Private helpers ── */

    private static function hydrate(array $row): Comment
    {
        return new Comment(
            id:           $row['id'],
            author:       $row['author'],
            content:      $row['content'],
            submissionId: $row['submission_id'],
            role:         $row['role'] ?? 'user',
            likes:        $row['likes'] ?? 0,
            likedByMe:    $row['liked_by_me'] ?? false,
            date:         new DateTime($row['date'])
        );
    }

    private static function seedData(): array
    {
        $now = new DateTime();
        return [
            [
                'id' => 1, 'author' => 'Jean Dupont', 'role' => 'jury',
                'content'       => 'Excellent travail ! La structure du code est très propre et l\'implémentation du refresh token est particulièrement bien pensée.',
                'submission_id' => 1, 'likes' => 6, 'liked_by_me' => false,
                'date'          => (clone $now)->modify('-2 hours')->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 2, 'author' => 'Sara Michelet', 'role' => 'user',
                'content'       => 'Très bonne soumission ! J\'aurais ajouté un rate-limiting sur les routes d\'authentification, mais la logique métier est impeccable.',
                'submission_id' => 1, 'likes' => 3, 'liked_by_me' => false,
                'date'          => (clone $now)->modify('-5 hours')->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 3, 'author' => 'Alice Martin', 'role' => 'owner',
                'content'       => 'Merci pour vos retours ! Je vais effectivement ajouter le rate-limiting dans la prochaine version. 🙏',
                'submission_id' => 1, 'likes' => 1, 'liked_by_me' => false,
                'date'          => (clone $now)->modify('-6 hours')->format('Y-m-d H:i:s'),
            ],
        ];
    }
}
