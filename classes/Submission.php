<?php

class Submission
{
    private int    $id;
    private string $title;
    private string $author;
    private string $category;
    private int    $views;
    private int    $likes;
    private DateTime $submittedAt;

    /** @var Comment[] */
    private array $comments = [];

    public function __construct(
        int    $id,
        string $title,
        string $author,
        string $category  = 'Défi',
        int    $views     = 0,
        int    $likes     = 0,
        ?DateTime $submittedAt = null
    ) {
        $this->id          = $id;
        $this->title       = $title;
        $this->author      = $author;
        $this->category    = $category;
        $this->views       = $views;
        $this->likes       = $likes;
        $this->submittedAt = $submittedAt ?? new DateTime();
    }

    /* ── Getters ── */
    public function getId(): int        { return $this->id; }
    public function getTitle(): string  { return htmlspecialchars($this->title); }
    public function getAuthor(): string { return htmlspecialchars($this->author); }
    public function getCategory(): string { return $this->category; }
    public function getViews(): int     { return $this->views; }
    public function getLikes(): int     { return $this->likes; }
    public function getFormattedDate(): string { return $this->submittedAt->format('d M Y'); }

    /* ── Gestion commentaires ── */
    public function addComment(Comment $comment): void
    {
        $this->comments[] = $comment;
    }

    public function removeComment(int $commentId): bool
    {
        foreach ($this->comments as $key => $comment) {
            if ($comment->getId() === $commentId) {
                unset($this->comments[$key]);
                $this->comments = array_values($this->comments);
                return true;
            }
        }
        return false;
    }

    public function getComment(int $commentId): ?Comment
    {
        foreach ($this->comments as $comment) {
            if ($comment->getId() === $commentId) return $comment;
        }
        return null;
    }

    /** @return Comment[] */
    public function getComments(): array { return $this->comments; }

    public function getCommentCount(): int { return count($this->comments); }
}
