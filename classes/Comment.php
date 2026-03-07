<?php

class Comment { private int $id_comment; private int $id_submission; private int $id_user; private string $content; private string $created_at;

public function __construct(int $id_comment, int $id_submission, int $id_user, string $content, string $created_at) {
    $this->id_comment = $id_comment;
    $this->id_submission = $id_submission;
    $this->id_user = $id_user;
    $this->content = $content;
    $this->created_at = $created_at;
}

public function getIdComment(): int {
    return $this->id_comment;
}

public function getIdSubmission(): int {
    return $this->id_submission;
}

public function getIdUser(): int {
    return $this->id_user;
}

public function getContent(): string {
    return $this->content;
}

public function getCreatedAt(): string {
    return $this->created_at;
}

public function setContent(string $content): void {
    $this->content = $content;
}

// Optionally, add methods to fetch author details by joining with user table in repository or service layer

}

?>