<?php
session_start();
try {
    $pdo = new PDO('mysql:host=localhost;dbname=formulaire', 'root', '');
    $sid = session_id();
    $produit = htmlspecialchars($_GET['produit'] ?? '');
    $prix = floatval($_GET['prix'] ?? 0);
    $userId = $_SESSION['user_id'] ?? null;

    if ($produit && $prix > 0) {
        $stmt = $pdo->prepare("SELECT id, quantite FROM panier WHERE session_id=? AND produit=?");
        $stmt->execute([$sid, $produit]);
        $existing = $stmt->fetch();
        if ($existing) {
            $pdo->prepare("UPDATE panier SET quantite=quantite+1 WHERE id=?")->execute([$existing['id']]);
        } else {
            $pdo->prepare("INSERT INTO panier (session_id, produit, prix, user_id) VALUES (?, ?, ?, ?)")->execute([$sid, $produit, $prix, $userId]);
        }
        if ($userId) {
            $points = (int) floor($prix);
            $pdo->prepare("UPDATE utilisateur SET points = points + ? WHERE id = ?")->execute([$points, $userId]);
        }
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
header("location: panier.php");
exit;
?>
