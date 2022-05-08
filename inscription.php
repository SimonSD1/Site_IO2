<?php

include ("mysql.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // verifie les infos
    if (empty($_POST["mdp"])){
        $reussite="pas de mot de passe";
    }
    else if (empty($_POST["pseudo"])){
        $reussite="pas de mail";
    }
    else{
        
        $pseudo = htmlspecialchars($_POST["pseudo"]); //evite les injections sql
        $mdp  = sha1(htmlspecialchars($_POST["mdp"])); // sha1 pour stocker le mdp de facon secutisé

        //on regarde le nombre d'utilisateur qui a le mdp et le pseudo donné
        $queryPseudo = "SELECT * FROM `utilisateurs` WHERE pseudo='$pseudo'";
        $resultPseudo = mysqli_query($conn,$queryPseudo) or die(mysql_error());
        $rowsPseudo = mysqli_num_rows($resultPseudo);

        $queryMdp = "SELECT * FROM `utilisateurs` WHERE mdp='$mdp'";
        $resultMdp = mysqli_query($conn,$queryMdp) or die(mysql_error());
        $rowsMdp = mysqli_num_rows($resultMdp);

        
        if($rowsPseudo==1 or $rowsMdp==1){
            $reussite="pseudo ou mdp deja pris ";
        }else{
            // si le mdp et le pseudo sont libre on ajoute l'utilisateur
            $injection = $conn->prepare("INSERT INTO utilisateurs (pseudo, mdp) VALUE(?, ?)");
            $injection->bind_param('ss', $pseudo, $mdp);
            if(!$injection->execute()){
                echo "error";
                $reussite="inscription echoué";
            }
            else{
                $reussite="Vous avez été inscrit avec succes   <a href=\"connexion.php\">connexion</a>";
            }
        }
    }
}
?>


<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>
    <form action="inscription.php" method="POST">
        <ul>
            <li>
                <input type="text" name="pseudo" placeholder="pseudo" >
            </li>
            <br>
            <li>
                <input type="password" name="mdp" placeholder="mdp" >
            </li>
            <br>
        <input type="submit" value="s'inscrire">
        </ul>
    </form>
    <br>
    <br>
    <p>
        <?php 
        if(isset($reussite)){ 
            echo $reussite; 
        }
        else{
            echo "<p>Vous avez deja un compte : <a href=\"connexion.php\">connexion</a></p>";
        }
        ?>
    </p>
    <a href="accueil.php">retour à l'accueil</a>
    <br>
</body>
</html>