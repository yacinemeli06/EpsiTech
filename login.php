<?php
session_start();
try {
    $connexion = new PDO('mysql:host=localhost;dbname=formulaire', 'root', '');
    if (isset($_POST['valider'])) {
        if (!empty($_POST['email']) && !empty($_POST['mdp'])) {
            $email = htmlspecialchars($_POST['email']);
            $stmt = $connexion->prepare("SELECT * FROM utilisateur WHERE email = ?");
            $stmt->execute(array($email));
            $user = $stmt->fetch();
            if ($user && password_verify($_POST['mdp'], $user['mot de passe'])) {
                $_SESSION['user'] = $user['nom'];
                header("location: accueil.html");
                exit;
            } else {
                echo "Email ou mot de passe incorrect.";
            }
        } else {
            echo "Veuillez remplir tous les champs.";
        }
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
