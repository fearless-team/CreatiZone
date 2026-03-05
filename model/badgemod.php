<?php
class BadgeModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Get all badges
    public function getAllBadges(): array
    {
        $stmt = $this->db->query("SELECT * FROM badges ORDER BY id_badge ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get badges for a user
    public function getUserBadges(int $user_id): array
    {
        $stmt = $this->db->prepare(
            "SELECT b.*, ub.awarded_at 
             FROM badges b
             JOIN user_badges ub ON b.id_badge = ub.badge_id
             WHERE ub.user_id = :user_id
             ORDER BY ub.awarded_at DESC"
        );
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Check if user already has a badge
    public function hasBadge(int $user_id, int $badge_id): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM user_badges 
             WHERE user_id = :user_id AND badge_id = :badge_id"
        );
        $stmt->execute([':user_id' => $user_id, ':badge_id' => $badge_id]);
        return (int)$stmt->fetchColumn() > 0;
    }

    // Award a badge to a user
    public function awardBadge(int $user_id, int $badge_id): bool
    {
        if ($this->hasBadge($user_id, $badge_id)) return false;
        $stmt = $this->db->prepare(
            "INSERT INTO user_badges (user_id, badge_id, awarded_at) 
             VALUES (:user_id, :badge_id, NOW())"
        );
        $stmt->execute([':user_id' => $user_id, ':badge_id' => $badge_id]);
        return true;
    }

    // Count submissions by user
    public function countUserSubmissions(int $user_id): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM submissions WHERE user_id = :user_id"
        );
        $stmt->execute([':user_id' => $user_id]);
        return (int)$stmt->fetchColumn();
    }

    // Count votes on a submission
    public function countSubmissionVotes(int $submission_id): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM votes WHERE submission_id = :id"
        );
        $stmt->execute([':id' => $submission_id]);
        return (int)$stmt->fetchColumn();
    }
}