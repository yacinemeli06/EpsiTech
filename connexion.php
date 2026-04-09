<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles.css">
    <title>Epsitech C</title>
</head>

<body>
    <DIV>
        <h1>CONNEXION</h1>
        <form action="config.php" method="POST">
            <label>Adresse Mail</label>
            <input type="email" name="email" require>
            <label>Mots de passe </label>
            <input type="password" name="mdp" require>
            <input type="submit" name="valider" value="connexion">
            <p>
                vous n'avez pas de compte?<a href="pageinsc.html">Inscrivez-vous ici</a>
            </p>
        </form>

    </DIV>
</body>

</html>