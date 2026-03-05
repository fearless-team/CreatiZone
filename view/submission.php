<?php
ini_set('display_errors', 0);
error_reporting(0);

$submissions    = $submissions    ?? [];
$submission     = $submission     ?? null;
$challenge_id   = $challenge_id   ?? 1;
$challenge_name = $challenge_name ?? 'CreatiZone';
$allChallenges  = $allChallenges  ?? [];
$sort           = $sort           ?? 'date';
$page_num       = $page_num       ?? 1;
$totalPages     = $totalPages     ?? 1;
$errors         = $errors         ?? [];

function renderFlash(): void {
    if (!empty($_SESSION['flash'])) {
        echo '<div class="alert alert-success flash-animate">' . htmlspecialchars($_SESSION['flash']) . '</div>';
        unset($_SESSION['flash']);
    }
    if (!empty($_SESSION['flash_error'])) {
        echo '<div class="alert alert-error flash-animate">' . htmlspecialchars($_SESSION['flash_error']) . '</div>';
        unset($_SESSION['flash_error']);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CreatiZone — Participations</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar" id="navbar">
  <div class="container nav-inner">
    <a href="index.php" class="navbar-brand">Creati<em>Zone</em></a>
    <span class="nav-tagline">Plateforme de participations créatives</span>
  </div>
</nav>

<button id="scrollTop" title="Retour en haut">↑</button>

<!-- Hero -->
<section class="hero">
  <div class="container hero-inner">
    <div>
      <p class="hero-sub">Défi en cours</p>
      <h1><?= htmlspecialchars($challenge_name) ?></h1>
    </div>
    <div class="hero-badge">
      <span class="count"><?= count($submissions) ?></span>
      <div class="label">participation<?= count($submissions) > 1 ? 's' : '' ?></div>
    </div>
  </div>
</section>

<!-- Sélecteur de défis -->
<div class="challenge-selector-wrap">
  <div class="container">
    <div class="challenge-selector">
      <span class="cs-label">Défi :</span>
      <div class="cs-tabs">
        <?php if (!empty($allChallenges)): ?>
          <?php foreach ($allChallenges as $ch): ?>
            <a href="index.php?page=submission&action=index&challenge_id=<?= (int)$ch['id_challenge'] ?>"
               class="cs-tab <?= $challenge_id == $ch['id_challenge'] ? 'active' : '' ?>">
              <span class="cs-num"><?= str_pad($ch['id_challenge'], 2, '0', STR_PAD_LEFT) ?></span>
              <?= htmlspecialchars($ch['titre']) ?>
            </a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="container" style="padding-top:1.2rem"><?php renderFlash(); ?></div>

<main class="main-wrap">
<div class="container">

  <?php
    $displayErrors = array_filter($errors ?? [], fn($e) =>
      stripos($e, 'déjà soumis') === false &&
      stripos($e, 'Une seule participation') === false
    );
  ?>
  <?php if (!empty($displayErrors)): ?>
    <div class="alert alert-error flash-animate" style="margin-bottom:1.5rem">
      <span>⚠</span>
      <div><strong>Erreur :</strong>
        <ul><?php foreach($displayErrors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
      </div>
    </div>
  <?php endif; ?>

  <div class="main-layout">

    <!-- FORMULAIRE -->
    <div class="form-col">
      <div class="form-card">
        <?php $alreadyDone = $alreadyDone ?? false; ?>

        <?php if ($alreadyDone): ?>
          <div class="ty-state">
            <div class="ty-icon">✦</div>
            <h2>Merci pour votre participation</h2>
            <p>Votre œuvre a été soumise avec succès.<br>Une seule participation par défi est autorisée.</p>
          </div>
        <?php else: ?>
          <div class="form-card-header">
            <div class="hicon">✦</div>
            <div>
              <h2>Soumettre une participation</h2>
              <p>Partagez votre création avec la communauté</p>
            </div>
          </div>
          <div class="form-card-body">
            <form action="index.php" method="POST" enctype="multipart/form-data" novalidate id="createForm">
              <input type="hidden" name="page"         value="submission">
              <input type="hidden" name="action"       value="store">
              <input type="hidden" name="challenge_id" value="<?= (int)$challenge_id ?>">
              <input type="hidden" name="csrf_token"   value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

              <div class="form-group">
                <label for="description">Description <span class="required">*</span></label>
                <textarea id="description" name="description" rows="5"
                          placeholder="Décrivez votre création en détail..."
                          required maxlength="2000"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                <span class="char-counter" id="descCounter">0 / 2000</span>
              </div>

              <div class="form-group">
                <label for="link">Lien externe</label>
                <input type="url" id="link" name="link" placeholder="https://..."
                       value="<?= htmlspecialchars($_POST['link'] ?? '') ?>">
                <span class="form-help">Portfolio, Behance, GitHub… (optionnel)</span>
              </div>

              <div class="form-group">
                <label>Image de présentation</label>
                <label for="image" class="file-drop-zone" id="fileDropZone">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/>
                    <line x1="12" y1="3" x2="12" y2="15"/>
                  </svg>
                  <span class="fdz-text">Cliquer ou glisser une image ici</span>
                  <span class="fdz-hint">JPG, PNG, GIF, WEBP — 5 Mo max</span>
                  <input type="file" id="image" name="image"
                         accept="image/jpeg,image/png,image/gif,image/webp" style="display:none">
                </label>
                <img id="imgPreview" class="img-preview" src="#" alt="Aperçu">
              </div>

              <div class="form-actions">
                  <a href="comment.php"><button class="btn btn-primary">commenter</button></a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                  Publier ma participation
                </button>
              </div>
            </form>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- LISTE -->
    <div class="list-col">

      <div class="list-header">
        <div>
          <p class="list-title">Participations</p>
          <span class="list-count"><?= count($submissions) ?> soumission<?= count($submissions) > 1 ? 's' : '' ?></span>
        </div>
        <div class="sort-tabs">
          <a href="index.php?page=submission&action=index&challenge_id=<?= (int)$challenge_id ?>&sort=date"
             class="sort-tab <?= $sort === 'date' ? 'active' : '' ?>">Récents</a>
          <a href="index.php?page=submission&action=index&challenge_id=<?= (int)$challenge_id ?>&sort=votes"
             class="sort-tab <?= $sort === 'votes' ? 'active' : '' ?>">★ Votes</a>
        </div>
      </div>

      <?php if (empty($submissions)): ?>
        <div class="empty-state">
          <div class="empty-icon">◇</div>
          <h3>Aucune participation pour l'instant</h3>
          <p>Soyez le premier à soumettre votre création pour ce défi.</p>
        </div>

      <?php else: ?>
        <div class="entry-list">
          <?php foreach ($submissions as $idx => $sub): ?>

            <div class="entry-wrap" id="ew-<?= (int)($sub['id'] ?? $sub['id_submission'] ?? 0) ?>">

              <article class="entry" style="animation-delay:<?= $idx * .07 ?>s">

                <?php if (!empty($sub['image'])): ?>
                  <div class="entry-thumb">
                    <img src="<?= htmlspecialchars($sub['image']) ?>" alt="Participation" loading="lazy">
                  </div>
                <?php else: ?>
                  <div class="entry-thumb entry-thumb--empty">◇</div>
                <?php endif; ?>

                <div class="entry-body">
                  <div class="entry-index"><?= str_pad($idx + 1, 2, '0', STR_PAD_LEFT) ?></div>
                  <p class="entry-desc"><?= nl2br(htmlspecialchars(mb_strimwidth($sub['description'] ?? '', 0, 200, '…'))) ?></p>
                  <div class="entry-meta">
                    <span class="entry-date">
                      <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                      </svg>
                      <?= !empty($sub['created_at']) ? date('d M Y', strtotime($sub['created_at'])) : '—' ?>
                    </span>
                    <?php if (!empty($sub['link'])): ?>
                      <a href="<?= htmlspecialchars($sub['link']) ?>" target="_blank" rel="noopener" class="entry-link">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                          <polyline points="15 3 21 3 21 9"/>
                          <line x1="10" y1="14" x2="21" y2="3"/>
                        </svg>
                        Voir le projet
                      </a>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="entry-actions">
                  <a href="index.php?page=submission&action=edit&id=<?= (int)($sub['id'] ?? $sub['id_submission'] ?? 0) ?>"
                     class="entry-btn entry-btn--edit" title="Modifier">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                  </a>
                  <form method="POST" action="index.php"
                        onsubmit="return confirm('Supprimer définitivement cette participation ?')">
                    <input type="hidden" name="page"         value="submission">
                    <input type="hidden" name="action"       value="delete">
                    <input type="hidden" name="id"           value="<?= (int)($sub['id'] ?? $sub['id_submission'] ?? 0) ?>">
                    <input type="hidden" name="challenge_id" value="<?= (int)$challenge_id ?>">
                    <input type="hidden" name="csrf_token"   value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                    <button type="submit" class="entry-btn entry-btn--delete" title="Supprimer">
                      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        <path d="M10 11v6"/>
                        <path d="M14 11v6"/>
                        <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                      </svg>
                    </button>
                  </form>
                </div>

              </article>

              <!-- Barre sociale visuelle -->
              <div class="social-bar">
                <button class="social-btn vote-btn"
                        data-id="<?= (int)($sub['id'] ?? $sub['id_submission'] ?? 0) ?>">
                  <svg class="star-icon" width="14" height="14" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                  Voter
                  <span class="social-badge vote-badge"><?= (int)($sub['vote_count'] ?? 0) ?></span>
                </button>

                <div class="social-sep"></div>

                <button class="social-btn comment-btn"
                        data-id="<?= (int)($sub['id'] ?? $sub['id_submission'] ?? 0) ?>">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                  </svg>
                  Commenter
                  <span class="social-badge comment-badge"><?= (int)($sub['comment_count'] ?? 0) ?></span>
                </button>
              </div>

            </div><!-- /entry-wrap -->

          <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
          <nav class="pagination">
            <?php if ($page_num > 1): ?>
              <a href="index.php?page=submission&action=index&challenge_id=<?= (int)$challenge_id ?>&p=<?= $page_num-1 ?>&sort=<?= $sort ?>" class="page-link">←</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
              <a href="index.php?page=submission&action=index&challenge_id=<?= (int)$challenge_id ?>&p=<?= $i ?>&sort=<?= $sort ?>"
                 class="page-link <?= $i === $page_num ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($page_num < $totalPages): ?>
              <a href="index.php?page=submission&action=index&challenge_id=<?= (int)$challenge_id ?>&p=<?= $page_num+1 ?>&sort=<?= $sort ?>" class="page-link">→</a>
            <?php endif; ?>
          </nav>
        <?php endif; ?>

      <?php endif; ?>
    </div>
  </div>
</div>
</main>

<!-- Timeline -->
<section class="timeline-section">
  <div class="container">
    <div class="section-heading">
      <h2>Historique chronologique</h2>
      <p>Toutes les participations dans l'ordre d'arrivée</p>
      <div class="gold-line"></div>
    </div>
    <?php if (empty($submissions)): ?>
      <p style="text-align:center;color:rgba(255,255,255,.28);font-size:.88rem">
        Aucune participation pour ce défi pour l'instant.
      </p>
    <?php else: ?>
      <div class="timeline">
        <?php foreach ($submissions as $i => $sub): ?>
          <div class="timeline-item <?= $i % 2 === 0 ? '' : 'right' ?>"
               style="animation-delay:<?= $i * .06 ?>s">
            <div class="timeline-dot"></div>
            <div class="timeline-card">
              <?php if (!empty($sub['image'])): ?>
                <img src="<?= htmlspecialchars($sub['image']) ?>" alt="" class="timeline-img">
              <?php endif; ?>
              <span class="timeline-date">
                <?= !empty($sub['created_at']) ? date('d M Y — H:i', strtotime($sub['created_at'])) : '—' ?>
              </span>
              <p class="timeline-desc">
                <?= nl2br(htmlspecialchars(mb_strimwidth($sub['description'] ?? '', 0, 160, '…'))) ?>
              </p>
              <?php if (!empty($sub['link'])): ?>
                <a href="<?= htmlspecialchars($sub['link']) ?>" target="_blank" rel="noopener"
                   style="display:inline-flex;align-items:center;gap:.3em;margin-top:.7rem;
                          font-size:.78rem;color:var(--green-4);
                          border:1px solid rgba(174,195,176,.3);
                          padding:.3rem .75rem;border-radius:var(--r-xl)">
                  ↗ Voir le projet
                </a>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<footer>
  <div class="container footer-inner">
    <span class="footer-brand">Creati<em>Zone</em></span>
    <span class="footer-copy">© <?= date('Y') ?> — Tous droits réservés</span>
  </div>
</footer>

<script src="script.js"></script>
</body>
</html>
