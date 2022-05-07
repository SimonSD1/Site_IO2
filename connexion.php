<?php

session_start();

include("mysql.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // verifie les infos
    if (empty($_POST["mdpCO"])){
        $reussite="pas de mot de passe";
    }
    else if (empty($_POST["pseudoCO"])){
        $reussite="pas de pseudo";
    }
    else{
        $pseudo = htmlspecialchars($_POST["pseudoCO"]);
        $mdp  = sha1(htmlspecialchars($_POST["mdpCO"]));

        $query = "SELECT * FROM `utilisateurs` WHERE pseudo='$pseudo' and mdp='$mdp'";
        $result = $conn->query($query) or die(mysql_error());
        $rows = mysqli_num_rows($result);
        $info = $result->fetch_assoc();
        if($rows==1){
            $_SESSION['pseudo'] = $pseudo;
            $_SESSION['id']=$info['id'];
            header("Location: acueille.php");
        }else{
        $reussite="Le nom d'utilisateur ou le mot de passe est incorrect.";
        }
    }
}


?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>

    <form action="connexion.php" method="POST">
        <ul>
            <li>
                <input type="text" name="pseudoCO" placeholder="pseudo" >
            </li>
            <br>
            <li>
                <input type="password" name="mdpCO" placeholder="mdp" >
            </li>
            <br>
        <input type="submit" name="connexion" value="connexion">
        </ul>
    </form>
    <br>
    <?php
    if(isset($reussite)){
        echo $reussite;
    } 
    ?>
    <br>
    <br>
    <p>Pas de compte : <a href="inscription.php">inscription</a></p>
</body>
</html>

