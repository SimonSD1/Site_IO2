<?php
session_start();
include("mysql.php");

function pageUtilisateurConnecte($conn){
    echo "
        <h1>Profil de ".$_SESSION["pseudo"]."</h1>
        <br>
        <br>
        <p>Mes avis :</p>
        ";
        // on affiche tout les avis qu'un utilisateur a mis
        $id = $_SESSION['id'];
        $demande = "SELECT avis.note, articles.titre FROM avis, articles WHERE avis.utilisateur='$id' and articles.id=avis.article;"; 
        $resultat = $conn->query($demande);
        while ($aviUtilisateur = $resultat->fetch_assoc()){
            echo $aviUtilisateur['titre']." : ".$aviUtilisateur['note'];
            echo "<br>";
        }
        echo "<br>
            <br>
            <a href=\"accueil.php\">retour a l'accueil</a>
        ";
}

?>


<!doctype html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Mon profil</title>
    </head>
    <body>

    <?php 
        if(!isset($_SESSION["pseudo"])){
            header("Location: accueil.php");
        }
        else{
            pageUtilisateurConnecte($conn);
        }
    ?>
    </body>
</html>