<?php
session_start();
$pointsUser = 0;
$connecte = isset($_SESSION['user_id']);
try {
    $pdo = new PDO('mysql:host=localhost;dbname=formulaire', 'root', '');
    if ($connecte) {
        $stmt = $pdo->prepare("SELECT points FROM utilisateur WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $pointsUser = $stmt->fetch()['points'] ?? 0;
    }
    $cadeaux = $pdo->query("SELECT * FROM cadeaux ORDER BY points_requis ASC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $cadeaux = [];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <link rel='stylesheet' href='accueil.css'>
    <title>Cadeaux - EpsiTech</title>
    <style>
        .container { max-width: 900px; margin: 100px auto 40px; padding: 20px; }
        h1 { color: #fff; font-family: 'Karmatic Arcade', sans-serif; text-align: center; margin-bottom: 10px; }
        .sous-titre { color: #aaa; text-align: center; margin-bottom: 30px; font-size: 14px; }
        .mes-points { text-align: center; margin-bottom: 30px; }
        .mes-points span { background: rgba(39,174,96,0.2); color: #27ae60; padding: 8px 20px; border-radius: 99px; font-size: 16px; font-weight: bold; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; }
        .card { background: rgba(255,255,255,0.05); border-radius: 14px; padding: 24px; text-align: center; position: relative; border: 2px solid transparent; }
        .card.dispo { border-color: #27ae60; background: rgba(39,174,96,0.08); }
        .card h3 { color: #fff; margin: 0 0 10px; font-size: 16px; }
        .card p { color: #aaa; font-size: 13px; margin: 0 0 16px; line-height: 1.5; }
        .pts-badge { display: inline-block; background: rgba(0,200,100,0.2); color: #94C9A9; padding: 6px 14px; border-radius: 99px; font-size: 14px; font-weight: bold; }
        .badge-dispo { display: block; margin-top: 12px; background: #27ae60; color: #fff; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: bold; }
        .badge-lock { display: block; margin-top: 12px; color: #e74c3c; font-size: 12px; }
        .cadeau-icon { font-size: 36px; margin-bottom: 12px; }
        .login-hint { text-align: center; margin-top: 30px; color: #aaa; font-size: 14px; }
        .login-hint a { color: #27ae60; }
    </style>
</head>
<body>
    <div id="groupe">
        <a class="text" href='accueil.html'>Accueil</a>
        <a class="text" href='panier.php'>Panier</a>
        <img id="taswira" src="logo.png" />
        <a class="text" href='catalogue.php'>Catalogue</a>
        <?php if ($connecte): ?>
        <a class="text" href='mes_points.php'>Mes Points</a>
        <a class="text" href='deconnexion.php'>Déconnexion</a>
        <?php else: ?>
        <a class="text" href='connexion.php'>Connexion</a>
        <?php endif; ?>
    </div>

    <div class="container">
        <h1>Cadeaux Fidélité</h1>
        <p class="sous-titre">1 point gagné pour chaque euro dépensé</p>

        <?php if ($connecte): ?>
        <div class="mes-points">
            <span>Mes points : <?= $pointsUser ?> pts</span>
        </div>
        <?php endif; ?>

        <div class="grid">
            <?php foreach ($cadeaux as $c):
                $dispo = $connecte && $pointsUser >= $c['points_requis'];
                $manquants = $c['points_requis'] - $pointsUser;
            ?>
            <div class="card <?= $dispo ? 'dispo' : '' ?>">
                <div class="cadeau-icon"><?= $c['points_requis'] >= 1000 ? '🎧' : ($c['points_requis'] >= 500 ? '🎮' : ($c['points_requis'] >= 300 ? '🕹️' : ($c['points_requis'] >= 100 ? '🎟️' : '🚚'))) ?></div>
                <h3><?= htmlspecialchars($c['nom']) ?></h3>
                <p><?= htmlspecialchars($c['description']) ?></p>
                <span class="pts-badge"><?= $c['points_requis'] ?> points</span>
                <?php if ($connecte): ?>
                    <?php if ($dispo): ?>
                        <span class="badge-dispo">✓ Disponible !</span>
                    <?php else: ?>
                        <span class="badge-lock">Il vous manque <?= $manquants ?> points</span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if (!$connecte): ?>
        <p class="login-hint"><a href="connexion.php">Connectez-vous</a> pour voir vos cadeaux disponibles.</p>
        <?php endif; ?>
    </div>
</body>
</html>
