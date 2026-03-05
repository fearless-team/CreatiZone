<?php
// badges.php - view
$badges     = $badges     ?? [];
$userBadges = $userBadges ?? [];
$userBadgeIds = array_column($userBadges, 'id_badge');
?>
<div class="badges-section">
  <div class="badges-header">
    <h3>🏅 Badges</h3>
    <span class="badges-count"><?= count($userBadges) ?> / <?= count($badges) ?> obtenus</span>
  </div>

  <?php if (!empty($_SESSION['new_badges'])): ?>
    <div class="alert alert-success flash-animate" style="margin-bottom:1rem">
      🎉 Nouveau badge obtenu : <strong><?= htmlspecialchars(implode(', ', $_SESSION['new_badges'])) ?></strong>
    </div>
    <?php unset($_SESSION['new_badges']); ?>
  <?php endif; ?>

  <div class="badges-grid">
    <?php foreach ($badges as $badge): ?>
      <?php $earned = in_array($badge['id_badge'], $userBadgeIds); ?>
      <div class="badge-card <?= $earned ? 'earned' : 'locked' ?>">
        <div class="badge-icon"><?= htmlspecialchars($badge['icon'] ?? '🏅') ?></div>
        <div class="badge-name"><?= htmlspecialchars($badge['name']) ?></div>
        <div class="badge-desc"><?= htmlspecialchars($badge['description']) ?></div>
        <?php if ($earned): ?>
          <div class="badge-date">
            Obtenu le <?= date('d M Y', strtotime($badge['awarded_at'] ?? 'now')) ?>
          </div>
        <?php else: ?>
          <div class="badge-locked">🔒 Non obtenu</div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>