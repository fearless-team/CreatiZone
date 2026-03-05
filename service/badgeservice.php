<?php
class BadgeService
{
    private BadgeModel $model;

    public function __construct(BadgeModel $model)
    {
        $this->model = $model;
    }

    /**
     * Call this after a user submits a participation.
     * Checks all badge rules and awards any earned badges.
     * Returns array of newly awarded badge names.
     */
    public function checkAndAward(int $user_id, int $submission_id): array
    {
        $awarded = [];

        // ── Badge 1: Première participation ──────────────────────
        // Awarded when user submits their very first participation
        $submissionCount = $this->model->countUserSubmissions($user_id);
        if ($submissionCount >= 1) {
            if ($this->model->awardBadge($user_id, 1)) {
                $awarded[] = '🥇 Première participation';
            }
        }

        // ── Badge 2: 10 participations ────────────────────────────
        // Awarded when user reaches 10 total submissions
        if ($submissionCount >= 10) {
            if ($this->model->awardBadge($user_id, 2)) {
                $awarded[] = '🏆 10 participations';
            }
        }

        // ── Badge 3: Participation populaire ─────────────────────
        // Awarded when a submission gets 5+ votes
        $votes = $this->model->countSubmissionVotes($submission_id);
        if ($votes >= 5) {
            if ($this->model->awardBadge($user_id, 3)) {
                $awarded[] = '⭐ Participation populaire';
            }
        }

        return $awarded;
    }
}