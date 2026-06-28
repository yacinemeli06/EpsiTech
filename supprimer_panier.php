<?php
session_start();
try {
    $pdo = new PDO('mysql:host=localhost;dbname=formulaire', 'root', '');
    $sid = session_id();
    $id = intval($_GET['id'] ?? 0);
    if ($id) {
        $pdo->prepare("DELETE FROM panier WHERE id=? AND session_id=?")->execute([$id, $sid]);
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
header("location: panier.php");
exit;
?>
