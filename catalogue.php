<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=formulaire', 'root', '');
    $stmt = $pdo->query("SELECT * FROM produits");
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $produits = [];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <link rel='stylesheet' type='text/css' media='screen' href='nouv.css'>
    <title>epsitech - Catalogue</title>
</head>
<body>
    <div id="groupe">
        <a class="text" href='accueil.html'>Accueil</a>
        <a class="text" href='panier.php'>panier</a>
        <img id="taswira" src="logo.png" />
        <a class="text" href='connexion.php'>cnx</a>
        <a class="text" href='catalogue.php'>nouveautes</a>
    </div>
    <div id="products">
        <?php foreach ($produits as $p): ?>
        <a href="<?= htmlspecialchars($p['page']) ?>">
            <img class="pici" src="<?= htmlspecialchars($p['image']) ?>" />
            <h1><?= htmlspecialchars($p['nom']) ?></h1>
            <h2>A partir de <?= number_format($p['prix'], 0) ?>€</h2>
        </a>
        <?php endforeach; ?>
    </div>
</body>
</html>
