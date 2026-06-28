<?php
session_start();
try {
    $pdo = new PDO('mysql:host=localhost;dbname=formulaire', 'root', '');
    $sid = session_id();
    $produit = htmlspecialchars($_GET['produit'] ?? '');
    $prix = floatval($_GET['prix'] ?? 0);

    if ($produit && $prix > 0) {
        $stmt = $pdo->prepare("SELECT id, quantite FROM panier WHERE session_id=? AND produit=?");
        $stmt->execute([$sid, $produit]);
        $existing = $stmt->fetch();
        if ($existing) {
            $pdo->prepare("UPDATE panier SET quantite=quantite+1 WHERE id=?")->execute([$existing['id']]);
        } else {
            $pdo->prepare("INSERT INTO panier (session_id, produit, prix) VALUES (?, ?, ?)")->execute([$sid, $produit, $prix]);
        }
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
header("location: panier.php");
exit;
?>
