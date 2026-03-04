<?php
$defi_a_modifier = $defi_a_modifier ?? null;
$defis           = $defis ?? [];
$message         = $message ?? '';
$categories      = $categories ?? ['Sport', 'Santé', 'Culture', 'Technologie', 'Finance', 'Autre'];
$session_user_id = (int)($_SESSION['user_id'] ?? 0);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CreatiZone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --p1-light:   #E7F5DC;
            --p1-soft:    #CFE1B9;
            --p1-mid:     #B6C99B;
            --p1-dark:    #728156;
            --p2-darkest: #0F2A1D;
            --p2-dark:    #375534;
            --p2-mid:     #6B9071;
            --p2-light:   #AEC3B0;
            --p2-cream:   #E3EED4;
            --surface:    #ffffff;
            --border:     var(--p1-soft);
            --text-dark:  var(--p2-darkest);
            --text-muted: var(--p2-mid);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--p2-cream);
            color: var(--text-dark);
            min-height: 100vh;
        }

        body::before {
            content: '';
            position: fixed; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 70% 50% at 0% 0%,   rgba(174,195,176,0.35) 0%, transparent 60%),
                radial-gradient(ellipse 50% 70% at 100% 100%, rgba(107,144,113,0.2) 0%, transparent 60%);
            pointer-events: none;
        }

        .container, nav { position: relative; z-index: 1; }

        /* ── NAVBAR ── */
        .navbar-main {
            background: var(--p2-darkest); height: 68px; padding: 0 2rem;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 4px 24px rgba(15,42,29,0.35);
            position: sticky; top: 0; z-index: 100;
        }
        .brand {
            font-family: 'Playfair Display', serif; font-size: 1.55rem; font-weight: 900;
            color: var(--p2-cream); text-decoration: none; letter-spacing: -0.5px;
            display: flex; align-items: center; gap: 8px;
        }
        .brand .dot { color: var(--p2-mid); }
        .nav-chip {
            background: rgba(227,238,212,0.1); border: 1px solid rgba(227,238,212,0.2);
            color: var(--p2-light); border-radius: 40px; padding: 6px 16px;
            font-size: 0.8rem; font-weight: 500; display: flex; align-items: center; gap: 6px;
        }

        /* ── HERO ── */
        .hero { padding: 3.5rem 0 1.5rem; text-align: center; }
        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--p1-light); color: var(--p2-dark);
            border-radius: 40px; padding: 5px 16px;
            font-size: 0.78rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: 1px; margin-bottom: 1rem;
        }
        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.2rem, 5vw, 3.5rem); font-weight: 900;
            color: var(--p2-darkest); line-height: 1.1; margin-bottom: 0.7rem;
        }
        .hero h1 em { font-style: italic; color: var(--p2-mid); }
        .hero p { color: var(--p2-mid); font-size: 1rem; max-width: 440px; margin: 0 auto; line-height: 1.6; }

        /* ── FORM ── */
        .form-wrap {
            background: var(--surface); border-radius: 22px;
            box-shadow: 0 0 0 1px rgba(55,85,52,0.08), 0 8px 40px rgba(15,42,29,0.12);
            overflow: hidden; margin-bottom: 3.5rem;
            animation: fadeUp 0.5s ease both;
        }
        .form-head {
            background: linear-gradient(135deg, var(--p2-darkest) 0%, var(--p2-dark) 100%);
            padding: 1.6rem 2rem; display: flex; align-items: center; gap: 14px;
        }
        .form-head-icon {
            width: 46px; height: 46px; background: rgba(227,238,212,0.15);
            border-radius: 12px; display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; color: var(--p2-cream); flex-shrink: 0;
        }
        .form-head h5 { font-family: 'Playfair Display', serif; font-weight: 700; color: var(--p2-cream); font-size: 1.2rem; margin: 0; }
        .form-head p  { color: var(--p2-light); font-size: 0.8rem; margin: 2px 0 0; }
        .form-body    { padding: 2rem; }

        /* Labels */
        .form-label {
            font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.8px; color: var(--p2-dark); margin-bottom: 6px; display: block;
        }

        /* Inputs & Select */
        .form-control,
        .form-select {
            border: 1.5px solid var(--border); border-radius: 11px;
            padding: 10px 14px; font-size: 0.92rem; color: var(--text-dark);
            background: #fafffe; font-family: 'DM Sans', sans-serif;
            transition: all 0.2s; width: 100%;
        }
        .form-control:focus,
        .form-select:focus {
            outline: none; border-color: var(--p2-mid);
            box-shadow: 0 0 0 3px rgba(107,144,113,0.18); background: white;
        }
        .form-control::placeholder { color: #aab8a2; }
        textarea.form-control { resize: vertical; min-height: 90px; }

        /* Select custom arrow */
        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%23375534' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 36px;
            cursor: pointer;
        }

        /* Option default (placeholder) */
        .form-select option[value=""] { color: #aab8a2; }

        /* File zone */
        .file-zone {
            position: relative; border: 2px dashed var(--p1-mid);
            border-radius: 12px; padding: 1.4rem; text-align: center;
            background: var(--p2-cream); cursor: pointer; transition: all 0.2s;
        }
        .file-zone:hover { border-color: var(--p2-mid); background: var(--p1-light); }
        .file-zone input[type="file"] { position: absolute; inset: 0; opacity: 0; width: 100%; height: 100%; cursor: pointer; }
        .file-zone .fi-icon { font-size: 1.8rem; color: var(--p2-mid); margin-bottom: 6px; }
        .file-zone p   { font-size: 0.82rem; color: var(--p2-mid); margin: 0; }
        .file-zone span { font-size: 0.72rem; color: var(--p2-light); }

        .image-preview-existing {
            width: 100%; max-height: 200px; object-fit: cover;
            border-radius: 10px; border: 2px solid var(--p1-mid); margin-bottom: 8px;
        }

        /* Submit */
        .btn-create {
            background: linear-gradient(135deg, var(--p2-darkest) 0%, var(--p2-dark) 100%);
            color: var(--p2-cream); border: none; border-radius: 12px;
            padding: 14px 28px; font-weight: 600; font-size: 0.95rem; width: 100%;
            cursor: pointer; transition: transform 0.15s, box-shadow 0.15s;
            box-shadow: 0 4px 18px rgba(15,42,29,0.3); font-family: 'DM Sans', sans-serif;
        }
        .btn-create:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(15,42,29,0.4); color: white; }

        /* ── SECTION BAR ── */
        .section-bar {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid var(--p1-soft);
        }
        .section-bar h2 { font-family: 'Playfair Display', serif; font-size: 1.7rem; font-weight: 900; color: var(--p2-darkest); margin: 0; }
        .section-bar h2 span { color: var(--p2-mid); font-style: italic; }
        .count-badge { background: var(--p2-darkest); color: var(--p2-cream); border-radius: 40px; padding: 5px 16px; font-size: 0.8rem; font-weight: 600; }

        /* ── CARDS ── */
        .card-defi {
            background: var(--surface); border: none; border-radius: 18px; overflow: hidden;
            box-shadow: 0 2px 16px rgba(15,42,29,0.08);
            transition: transform 0.25s cubic-bezier(.34,1.56,.64,1), box-shadow 0.25s;
            height: 100%; display: flex; flex-direction: column;
        }
        .card-defi:hover { transform: translateY(-7px) scale(1.015); box-shadow: 0 16px 44px rgba(15,42,29,0.16); }

        .card-img-wrap { position: relative; height: 185px; overflow: hidden; }
        .card-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
        .card-defi:hover .card-img-wrap img { transform: scale(1.08); }
        .card-img-wrap::after {
            content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 65%;
            background: linear-gradient(to top, rgba(15,42,29,0.6), transparent); pointer-events: none;
        }
        .img-cat {
            position: absolute; top: 12px; left: 12px; z-index: 2;
            background: white; color: var(--p2-dark); font-size: 0.72rem; font-weight: 700;
            border-radius: 30px; padding: 4px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .img-deadline {
            position: absolute; bottom: 12px; right: 12px; z-index: 2;
            background: rgba(15,42,29,0.75); backdrop-filter: blur(6px);
            color: var(--p2-cream); font-size: 0.72rem; font-weight: 600;
            border-radius: 30px; padding: 4px 12px; display: flex; align-items: center; gap: 5px;
        }

        .card-body-inner { padding: 1.2rem 1.4rem; flex: 1; }
        .card-body-inner h5 { font-family: 'Playfair Display', serif; font-weight: 700; font-size: 1.1rem; color: var(--p2-darkest); margin-bottom: 6px; line-height: 1.3; }
        .card-body-inner p { font-size: 0.84rem; color: var(--text-muted); line-height: 1.55; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin: 0; }

        .card-foot { background: var(--p2-cream); border-top: 1px solid var(--p1-soft); padding: 0.8rem 1.4rem; display: flex; gap: 8px; }

        .btn-edit {
            flex: 1; background: transparent; border: 1.5px solid var(--p2-dark); color: var(--p2-dark);
            border-radius: 9px; padding: 7px 10px; font-size: 0.81rem; font-weight: 600;
            text-decoration: none; text-align: center; display: flex; align-items: center; justify-content: center; gap: 5px;
            transition: all 0.15s; font-family: 'DM Sans', sans-serif;
        }
        .btn-edit:hover { background: var(--p2-dark); color: white; }

        .btn-del {
            flex: 1; background: transparent; border: 1.5px solid #c0392b; color: #c0392b;
            border-radius: 9px; padding: 7px 10px; font-size: 0.81rem; font-weight: 600;
            text-decoration: none; text-align: center; display: flex; align-items: center; justify-content: center; gap: 5px;
            transition: all 0.15s; font-family: 'DM Sans', sans-serif;
        }
        .btn-del:hover { background: #c0392b; color: white; }

        /* ── EMPTY ── */
        .empty-state { text-align: center; padding: 5rem 2rem; background: white; border-radius: 20px; box-shadow: 0 2px 16px rgba(15,42,29,0.08); }
        .empty-icon { width: 90px; height: 90px; background: var(--p2-cream); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 2.4rem; color: var(--p2-mid); margin-bottom: 1.2rem; }
        .empty-state h4 { font-family: 'Playfair Display', serif; color: var(--p2-darkest); margin-bottom: 8px; }
        .empty-state p { color: var(--p2-mid); font-size: 0.9rem; }

        /* ── ALERTS ── */
        .alert-ok  { background:#edf7ee; border:1px solid #a8d5ab; color:#1e5c25; border-radius:10px; padding:10px 16px; font-size:0.88rem; margin-bottom:1rem; display:flex; align-items:center; gap:8px; }
        .alert-err { background:#fdf0ef; border:1px solid #f5b7b1; color:#922b21; border-radius:10px; padding:10px 16px; font-size:0.88rem; margin-bottom:1rem; display:flex; align-items:center; gap:8px; }

        /* ── ANIMATIONS ── */
        @keyframes fadeUp { from { opacity:0; transform:translateY(24px); } to { opacity:1; transform:translateY(0); } }
        .anim-1 { animation: fadeUp 0.5s ease both; }
        .anim-2 { animation: fadeUp 0.5s 0.1s ease both; }
        .anim-3 { animation: fadeUp 0.5s 0.2s ease both; }
        .anim-4 { animation: fadeUp 0.5s 0.3s ease both; }

        .page-footer { margin-top:4rem; padding:1.5rem 0; text-align:center; font-size:0.78rem; color:var(--p2-mid); border-top:1px solid var(--p1-soft); }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar-main">
    <a class="brand" href="#">
        <i class="bi bi-patch-check-fill" style="color:var(--p2-mid);font-size:1.3rem;"></i>
        Creati<span class="dot">Zone</span>
    </a>
    <div class="d-flex gap-2">
        <span class="nav-chip"><i class="bi bi-trophy"></i> <?= count($defis) ?> défis</span>
        <span class="nav-chip"><i class="bi bi-person-circle"></i> Utilisateur #<?= $session_user_id ?></span>
    </div>
</nav>

<div class="container py-2" style="max-width:1080px;">

    <!-- HERO -->
    <div class="hero anim-1">
        <div class="hero-eyebrow"><i class="bi bi-stars"></i> Plateforme de défis personnels</div>
        <h1>Relevez vos <em>défis,</em><br>transformez vos habitudes.</h1>
        <p>Créez, suivez et accomplissez vos objectifs au quotidien.</p>
    </div>

    <!-- FORMULAIRE -->
    <div class="row justify-content-center mb-5 anim-2">
        <div class="col-lg-7">
            <div class="form-wrap">
                <div class="form-head">
                    <div class="form-head-icon">
                        <i class="bi bi-<?= $defi_a_modifier ? 'pencil' : 'plus-lg' ?>"></i>
                    </div>
                    <div>
                        <h5><?= $defi_a_modifier ? 'Modifier le défi' : 'Créer un nouveau défi' ?></h5>
                        <p>Remplissez tous les champs ci-dessous</p>
                    </div>
                </div>
                <div class="form-body">

                    <?= $message ?>

                    <form method="POST" action="" enctype="multipart/form-data">

                        <?php if ($defi_a_modifier): ?>
                            <input type="hidden" name="id" value="<?= (int)$defi_a_modifier['id'] ?>">
                            <input type="hidden" name="ancienne_image" value="<?= htmlspecialchars($defi_a_modifier['image'] ?? '') ?>">
                        <?php endif; ?>

                        <!-- Titre -->
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="bi bi-type me-1"></i>Titre du défi
                            </label>
                            <input type="text" name="titre" class="form-control"
                                   placeholder="Ex : 30 jours de méditation"
                                   value="<?= htmlspecialchars($defi_a_modifier['titre'] ?? '') ?>" required>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="bi bi-text-paragraph me-1"></i>Description
                            </label>
                            <textarea name="description" class="form-control"
                                      placeholder="Décrivez votre défi en quelques mots..." required><?= htmlspecialchars($defi_a_modifier['description'] ?? '') ?></textarea>
                        </div>

                        <div class="row g-3 mb-3">

                            <!-- ✅ Catégorie — SELECT au lieu de input text -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-folder me-1"></i>Catégorie
                                </label>
                                <select name="categorie" class="form-select" required>
                                    <option value="" disabled <?= empty($defi_a_modifier['categorie']) ? 'selected' : '' ?>>
                                        — Choisir une catégorie —
                                    </option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= htmlspecialchars($cat) ?>"
                                            <?= (isset($defi_a_modifier['categorie']) && $defi_a_modifier['categorie'] === $cat) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Deadline -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-calendar me-1"></i>Deadline
                                </label>
                                <input type="date" name="deadline" class="form-control"
                                       value="<?= htmlspecialchars($defi_a_modifier['deadline'] ?? '') ?>" required>
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-image me-1"></i>Image du défi
                            </label>
                            <?php if (!empty($defi_a_modifier['image'])): ?>
                                <img src="/DS1/uploads/<?= htmlspecialchars($defi_a_modifier['image']) ?>"
                                     class="image-preview-existing d-block" alt="Image actuelle">
                                <small class="text-muted d-block mb-2">
                                    <i class="bi bi-info-circle me-1"></i>Une nouvelle image remplacera celle-ci
                                </small>
                            <?php endif; ?>
                            <div class="file-zone">
                                <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
                                <div class="fi-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                                <p>Glissez une image ou cliquez pour choisir</p>
                                <span>JPG, PNG, WEBP — max 2 Mo</span>
                            </div>
                        </div>

                        <button type="submit" name="submit" class="btn-create">
                            <i class="bi bi-<?= $defi_a_modifier ? 'save' : 'rocket-takeoff' ?> me-2"></i>
                            <?= $defi_a_modifier ? 'Enregistrer les modifications' : 'Lancer le défi' ?>
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- LISTE -->
    <div class="section-bar anim-3">
        <h2>Tous les <span>défis</span></h2>
        <span class="count-badge"><?= count($defis) ?> défi<?= count($defis) > 1 ? 's' : '' ?></span>
    </div>

    <?php if (!empty($defis)): ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 anim-4">
            <?php foreach ($defis as $defi):
                $owner = (int)$defi['user_id'] === $session_user_id;
            ?>
            <div class="col">
                <div class="card-defi">
                    <div class="card-img-wrap">
                        <?php if (!empty($defi['image'])): ?>
                            <img src="/DS1/uploads/<?= htmlspecialchars($defi['image']) ?>"
                                 alt="<?= htmlspecialchars($defi['titre']) ?>">
                        <?php else: ?>
                            <img src="https://placehold.co/400x185/6B9071/E3EED4?text=<?= urlencode($defi['titre']) ?>"
                                 alt="<?= htmlspecialchars($defi['titre']) ?>">
                        <?php endif; ?>
                        <span class="img-cat">
                            <i class="bi bi-folder me-1"></i><?= htmlspecialchars($defi['categorie']) ?>
                        </span>
                        <span class="img-deadline">
                            <i class="bi bi-calendar-event"></i><?= htmlspecialchars($defi['deadline']) ?>
                        </span>
                    </div>
                    <div class="card-body-inner">
                        <h5><?= htmlspecialchars($defi['titre']) ?></h5>
                        <p><?= htmlspecialchars($defi['description']) ?></p>
                    </div>
                    <?php if ($owner): ?>
                    <div class="card-foot">
                        <a href="?modifier=<?= (int)$defi['id'] ?>" class="btn-edit">
                            <i class="bi bi-pencil"></i> Modifier
                        </a>
                        <a href="?supprimer=<?= (int)$defi['id'] ?>" class="btn-del"
                           onclick="return confirm('Supprimer ce défi ?');">
                            <i class="bi bi-trash"></i> Supprimer
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <div class="empty-state anim-4">
            <div class="empty-icon"><i class="bi bi-journal-x"></i></div>
            <h4>Aucun défi pour le moment</h4>
            <p>Soyez le premier à créer un défi !</p>
        </div>
    <?php endif; ?>

    <div class="page-footer">🌿 CreatiZone &nbsp;·&nbsp; Votre compagnon de défis personnels</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>