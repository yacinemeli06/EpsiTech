<?php
try {//permet d'eviter l'affichage d'erreur trop detaillé a l'utilisateur
   //etablir la connexion avec le serveur
    $connexion = new PDO('mysql:host=localhost;dbname=formulaire', 'root', '');
    if (isset($_POST['valider'])) {
        if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['email']) && !empty($_POST['mdp'])) {
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $email = htmlspecialchars($_POST['email']);
            $password = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

            if (strlen($_POST['mdp']) < 7) {
                echo "Votre mot de passe n'est pas assez sécurisé.";
            } elseif (strlen($nom) > 25 || strlen($prenom) > 25) {
                echo "Votre nom ou prénom est trop long !";
            } else {
                $insertion = $connexion->prepare("INSERT INTO utilisateur(`nom`, `prenom`, `email`, `mot de passe`) VALUES(?, ?, ?, ?)");
                $insertion->execute(array($nom, $prenom, $email, $password));
                header("location: connexion.php");
                exit;
            }
        } else {
            echo "Veuillez remplir tous les champs.";
        }
    }
} catch (PDOException $e) {//affiche un message prevu pour l'utilisateur 
    echo "Erreur : " . $e->getMessage();
}
?>

