<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("location: connexion.php");
    exit;
}
try {
    $pdo = new PDO('mysql:host=localhost;dbname=formulaire', 'root', '');

    $CA = $pdo->query("SELECT SUM(prix * quantite) AS total FROM panier")->fetch()['total'] ?? 0;
    $nbPaniers = $pdo->query("SELECT COUNT(DISTINCT session_id) AS nb FROM panier")->fetch()['nb'] ?? 0;
    $nbArticles = $pdo->query("SELECT SUM(quantite) AS nb FROM panier")->fetch()['nb'] ?? 0;

    $stmtProduits = $pdo->query("SELECT produit, SUM(quantite) AS total_vendu, SUM(prix * quantite) AS ca_produit FROM panier GROUP BY produit ORDER BY ca_produit DESC");
    $produitsVendus = $stmtProduits->fetchAll(PDO::FETCH_ASSOC);

    $stmtClients = $pdo->query("SELECT u.nom, u.prenom, u.email, SUM(p.prix * p.quantite) AS ca_client FROM panier p JOIN utilisateur u ON p.user_id = u.id GROUP BY u.id, u.nom, u.prenom, u.email ORDER BY ca_client DESC LIMIT 5");
    $topClients = $stmtClients->fetchAll(PDO::FETCH_ASSOC);

    $stmtMois = $pdo->query("SELECT DATE_FORMAT(date_ajout, '%Y-%m') AS mois, produit, SUM(quantite) AS total_vendu, SUM(prix * quantite) AS ca FROM panier WHERE date_ajout >= DATE_SUB(NOW(), INTERVAL 3 MONTH) GROUP BY mois, produit ORDER BY mois DESC, total_vendu DESC");
    $parMois = $stmtMois->fetchAll(PDO::FETCH_ASSOC);

    $top10ParMois = [];
    foreach ($parMois as $row) {
        if (!isset($top10ParMois[$row['mois']]) || count($top10ParMois[$row['mois']]) < 10) {
            $top10ParMois[$row['mois']][] = $row;
        }
    }

} catch (PDOException $e) {
    $CA = 0; $nbPaniers = 0; $nbArticles = 0; $produitsVendus = []; $topClients = []; $top10ParMois = [];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <link rel='stylesheet' href='accueil.css'>
    <title>Dashboard - EpsiTech</title>
    <style>
        .dash-container { max-width: 960px; margin: 100px auto 40px; padding: 20px; }
        h1 { color: #fff; font-family: 'Karmatic Arcade', sans-serif; text-align: center; margin-bottom: 30px; }
        h2 { color: #94C9A9; margin: 30px 0 10px; border-bottom: 1px solid rgba(148,201,169,0.3); padding-bottom: 6px; }
        .kpi-grid { display: flex; gap: 20px; margin-bottom: 30px; }
        .kpi { flex: 1; background: rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; text-align: center; }
        .kpi .val { font-size: 32px; font-weight: bold; color: #27ae60; }
        .kpi .lbl { color: #aaa; margin-top: 6px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; background: rgba(255,255,255,0.05); border-radius: 12px; overflow: hidden; margin-bottom: 30px; }
        th { background: rgba(0,200,100,0.3); color: #fff; padding: 12px; text-align: left; }
        td { color: #fff; padding: 12px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .vide { color: #aaa; text-align: center; padding: 20px; }
        .user-info { color: #aaa; text-align: right; margin-bottom: 10px; }
        .btn-deco { background: #e74c3c; color: #fff; padding: 6px 14px; border-radius: 8px; text-decoration: none; font-size: 13px; }
        .mois-label { background: rgba(0,200,100,0.15); color: #94C9A9; padding: 6px 12px; font-weight: bold; font-size: 13px; }
        .rank { color: #27ae60; font-weight: bold; }
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

    <div class="dash-container">
        <p class="user-info">Connecté : <?= htmlspecialchars($_SESSION['user']) ?> — <a class="btn-deco" href="deconnexion.php">Se déconnecter</a></p>
        <h1>Tableau de bord des ventes</h1>

        <div class="kpi-grid">
            <div class="kpi">
                <div class="val"><?= number_format($CA, 2) ?> €</div>
                <div class="lbl">Chiffre d'affaires total</div>
            </div>
            <div class="kpi">
                <div class="val"><?= $nbPaniers ?></div>
                <div class="lbl">Paniers (clients)</div>
            </div>
            <div class="kpi">
                <div class="val"><?= $nbArticles ?></div>
                <div class="lbl">Articles vendus</div>
            </div>
        </div>

        <h2>CA par produit</h2>
        <?php if (empty($produitsVendus)): ?>
            <p class="vide">Aucune vente enregistrée.</p>
        <?php else: ?>
        <table>
            <thead><tr><th>#</th><th>Produit</th><th>Qté vendue</th><th>CA généré</th></tr></thead>
            <tbody>
                <?php foreach ($produitsVendus as $i => $p): ?>
                <tr>
                    <td class="rank"><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($p['produit']) ?></td>
                    <td><?= $p['total_vendu'] ?></td>
                    <td><?= number_format($p['ca_produit'], 2) ?> €</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>

        <h2>Top 5 clients par CA</h2>
        <?php if (empty($topClients)): ?>
            <p class="vide">Aucun client identifié (nécessite connexion au moment de l'achat).</p>
        <?php else: ?>
        <table>
            <thead><tr><th>#</th><th>Nom</th><th>Prénom</th><th>Email</th><th>CA généré</th></tr></thead>
            <tbody>
                <?php foreach ($topClients as $i => $c): ?>
                <tr>
                    <td class="rank"><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($c['nom']) ?></td>
                    <td><?= htmlspecialchars($c['prenom']) ?></td>
                    <td><?= htmlspecialchars($c['email']) ?></td>
                    <td><?= number_format($c['ca_client'], 2) ?> €</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>

        <h2>Top 10 produits par mois (3 derniers mois)</h2>
        <?php if (empty($top10ParMois)): ?>
            <p class="vide">Aucune donnée sur les 3 derniers mois.</p>
        <?php else: ?>
        <table>
            <thead><tr><th>Mois</th><th>#</th><th>Produit</th><th>Qté vendue</th><th>CA</th></tr></thead>
            <tbody>
                <?php foreach ($top10ParMois as $mois => $lignes): ?>
                    <?php foreach ($lignes as $i => $l): ?>
                    <tr>
                        <?php if ($i === 0): ?>
                        <td class="mois-label" rowspan="<?= count($lignes) ?>"><?= htmlspecialchars($mois) ?></td>
                        <?php endif; ?>
                        <td class="rank"><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($l['produit']) ?></td>
                        <td><?= $l['total_vendu'] ?></td>
                        <td><?= number_format($l['ca'], 2) ?> €</td>
                    </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</body>
</html>
