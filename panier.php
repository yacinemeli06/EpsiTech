<?php
session_start();
try {
    $pdo = new PDO('mysql:host=localhost;dbname=formulaire', 'root', '');
    $sid = session_id();
    $stmt = $pdo->prepare("SELECT * FROM panier WHERE session_id=? ORDER BY date_ajout DESC");
    $stmt->execute([$sid]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $total = array_sum(array_map(fn($i) => $i['prix'] * $i['quantite'], $items));
} catch (PDOException $e) {
    $items = [];
    $total = 0;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <link rel='stylesheet' href='accueil.css'>
    <title>Mon Panier - EpsiTech</title>
    <style>
        .panier-container { max-width: 800px; margin: 100px auto 40px; padding: 20px; }
        h1 { color: #fff; font-family: 'Karmatic Arcade', sans-serif; text-align: center; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; background: rgba(255,255,255,0.05); border-radius: 12px; overflow: hidden; }
        th { background: rgba(0,200,100,0.3); color: #fff; padding: 14px; text-align: left; }
        td { color: #fff; padding: 14px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .btn-suppr { background: #e74c3c; color: #fff; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; text-decoration: none; font-size: 14px; }
        .btn-suppr:hover { background: #c0392b; }
        .total { color: #fff; font-size: 22px; text-align: right; margin-top: 20px; font-weight: bold; }
        .vide { color: #aaa; text-align: center; padding: 40px; font-size: 18px; }
        .btn-continuer { display: block; width: fit-content; margin: 20px auto 0; background: #27ae60; color: #fff; padding: 12px 28px; border-radius: 10px; text-decoration: none; font-size: 16px; }
        .btn-continuer:hover { background: #1e8449; }
    </style>
</head>
<body>
    <div id="groupe">
        <a class="text" href='accueil.html'>Accueil</a>
        <a class="text" href='panier.php'>panier</a>
        <img id="taswira" src="logo.png" />
        <a class="text" href='pageconnection.html'>cnx</a>
        <a class="text" href='nouv.html'>nouveautes</a>
    </div>

    <div class="panier-container">
        <h1>Mon Panier</h1>
        <?php if (empty($items)): ?>
            <p class="vide">Votre panier est vide.</p>
            <a class="btn-continuer" href="accueil.html">Continuer mes achats</a>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix unitaire</th>
                        <th>Quantite</th>
                        <th>Sous-total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['produit']) ?></td>
                        <td><?= number_format($item['prix'], 2) ?> €</td>
                        <td><?= $item['quantite'] ?></td>
                        <td><?= number_format($item['prix'] * $item['quantite'], 2) ?> €</td>
                        <td>
                            <a class="btn-suppr" href="supprimer_panier.php?id=<?= $item['id'] ?>">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p class="total">Total : <?= number_format($total, 2) ?> €</p>
            <a class="btn-continuer" href="accueil.html">Continuer mes achats</a>
        <?php endif; ?>
    </div>
</body>
</html>
