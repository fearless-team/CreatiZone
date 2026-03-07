<?php
/**
 * View partielle : affiche une carte commentaire.
 * @var Comment $comment
 * @var bool    $canDelete
 */
$badge = $comment->getBadge();
?>
<article class="comment-item" data-id="<?= $comment->getId() ?>">
    <div class="avatar" style="background:<?= $comment->getColor() ?>">
        <?= $comment->getInitials() ?>
    </div>

    <div class="comment-body">
        <header class="comment-header">
            <span class="comment-author"><?= htmlspecialchars($comment->getAuthor()) ?></span>

            <?php if ($badge): ?>
                <span class="comment-badge <?= $badge['class'] ?>"><?= $badge['label'] ?></span>
            <?php endif; ?>

            <time class="comment-date"><?= $comment->getFormattedDate() ?></time>
        </header>

        <p class="comment-text"><?= nl2br($comment->getContent()) ?></p>

        <footer class="comment-footer">
            <button class="reaction-btn like-btn <?= $comment->isLikedByMe() ? 'liked' : '' ?>"
                    data-id="<?= $comment->getId() ?>">
                ❤️ <span class="like-count"><?= $comment->getLikes() ?></span>
            </button>

            <button class="reaction-btn reply-toggle" data-id="<?= $comment->getId() ?>">
                💬 Répondre
            </button>

            <?php if ($canDelete ?? false): ?>
                <button class="delete-btn" data-id="<?= $comment->getId() ?>">🗑</button>
            <?php endif; ?>
        </footer>

        <div class="reply-form" id="reply-<?= $comment->getId() ?>">
            <input type="text" placeholder="Votre réponse…" class="reply-input">
            <button class="btn btn-submit reply-send">↩</button>
        </div>
    </div>
</article>
