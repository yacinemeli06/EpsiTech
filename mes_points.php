<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("location: connexion.php");
    exit;
}
try {
    $pdo = new PDO('mysql:host=localhost;dbname=formulaire', 'root', '');
    $stmt = $pdo->prepare("SELECT points FROM utilisateur WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $points = $stmt->fetch()['points'] ?? 0;

    $stmtProchain = $pdo->prepare("SELECT nom, points_requis FROM cadeaux WHERE points_requis > ? ORDER BY points_requis ASC LIMIT 1");
    $stmtProchain->execute([$points]);
    $prochain = $stmtProchain->fetch();
} catch (PDOException $e) {
    $points = 0; $prochain = null;
}
$manquants = $prochain ? $prochain['points_requis'] - $points : 0;
$progressPct = $prochain ? min(100, round(($points / $prochain['points_requis']) * 100)) : 100;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <link rel='stylesheet' href='accueil.css'>
    <title>Mes Points - EpsiTech</title>
    <style>
        .container { max-width: 700px; margin: 100px auto 40px; padding: 20px; }
        h1 { color: #fff; font-family: 'Karmatic Arcade', sans-serif; text-align: center; margin-bottom: 30px; }
        .points-card { background: rgba(255,255,255,0.05); border-radius: 16px; padding: 40px; text-align: center; margin-bottom: 30px; }
        .points-val { font-size: 72px; font-weight: bold; color: #27ae60; }
        .points-lbl { color: #aaa; font-size: 16px; margin-top: 8px; }
        .progress-block { margin-top: 30px; }
        .progress-lbl { color: #fff; font-size: 14px; margin-bottom: 8px; }
        .progress-bar { background: rgba(255,255,255,0.1); border-radius: 99px; height: 16px; overflow: hidden; }
        .progress-fill { background: #27ae60; height: 100%; border-radius: 99px; transition: width 0.5s; }
        .prochain-info { color: #94C9A9; margin-top: 10px; font-size: 14px; }
        .btn { display: inline-block; margin-top: 24px; background: #27ae60; color: #fff; padding: 12px 28px; border-radius: 10px; text-decoration: none; font-size: 15px; }
        .btn:hover { background: #1e8449; }
        .all-done { color: #27ae60; font-size: 16px; margin-top: 20px; }
    </style>
</head>
<body>
    <div id="groupe">
        <a class="text" href='accueil.html'>Accueil</a>
        <a class="text" href='panier.php'>Panier</a>
        <img id="taswira" src="logo.png" />
        <a class="text" href='catalogue.php'>Catalogue</a>
        <a class="text" href='deconnexion.php'>Déconnexion</a>
    </div>

    <div class="container">
        <h1>Mes Points Fidélité</h1>

        <div class="points-card">
            <div class="points-val"><?= $points ?></div>
            <div class="points-lbl">points accumulés</div>
            <div class="points-lbl" style="margin-top:6px;font-size:12px;">1 point = 1 € dépensé</div>

            <?php if ($prochain): ?>
            <div class="progress-block">
                <p class="progress-lbl">Prochain cadeau : <strong style="color:#94C9A9"><?= htmlspecialchars($prochain['nom']) ?></strong> (<?= $prochain['points_requis'] ?> pts)</p>
                <div class="progress-bar">
                    <div class="progress-fill" style="width:<?= $progressPct ?>%"></div>
                </div>
                <p class="prochain-info">Il vous manque <strong><?= $manquants ?> points</strong> pour débloquer ce cadeau.</p>
            </div>
            <?php else: ?>
            <p class="all-done">Vous avez débloqué tous les cadeaux disponibles !</p>
            <?php endif; ?>

            <a class="btn" href="cadeaux.php">Voir mes cadeaux disponibles</a>
        </div>
    </div>
</body>
</html>
