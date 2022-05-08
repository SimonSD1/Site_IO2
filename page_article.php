<?php
    session_start();

    if(!isset($_SESSION["pseudo"])){
        header("Location: accueil.php");
    }
    

    include("mysql.php");
    include("fonctionCourante.php");

    if(!isset($_GET['article']) ){
        header("Location: accueil.php");
    }
    if(empty($_GET['article']) ){
        header("Location: accueil.php");
    }

    //pour mettre la note pareil
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $note = $_POST['note'];
        $article = $_POST['article'];
        $id=$_SESSION['id'];
        ajouteNote($conn, $note, $article, $id);

    }

    // recuper le nom de l'article a partir de son id
    function nom($conn){
        $article_id=$_GET['article'];
        $demande="SELECT titre FROM articles WHERE id='$article_id'";
        $resultat = $conn->query($demande);
        $resultat = $resultat->fetch_array()[0];
        echo $resultat;
    }

    // affiche toute les notes d'un article
    function affiche_avis($conn){
        $article_id=$_GET['article'];
        $demande="SELECT avis.note, utilisateurs.pseudo FROM avis, utilisateurs WHERE avis.article='$article_id' and utilisateurs.id=avis.utilisateur";
        $resultat = $conn->query($demande);
        while($avisUtilisateur = $resultat->fetch_assoc()){
            echo $avisUtilisateur['pseudo']." a mis la note de : ".$avisUtilisateur['note'];
            echo "<form action =\"\" method=\"POST\">
                <input type=\"hidden\" name=\"note\" value=\"".$avisUtilisateur['note']."\">
                <input type=\"hidden\" name=\"article\" value=\"".$article_id."\">
                <input type=\"submit\" value=\"pareil\">
            ";
            echo "<br>";
        }
    }

    function afficheTexte($conn){
        $article_id=$_GET['article'];
        $demande="SELECT texte FROM articles WHERE id='$article_id'";
        $resultat = $conn->query($demande);
        $resultat = $resultat->fetch_array()[0];
        echo $resultat;
    }
    
?>

<!DOCTYPE html>
<html>
    <body>
        <h1><?php nom($conn) ?></h1>
        <br>
        <?php
            afficheTexte($conn);
        ?>
        <br>
        <br>
        <?php
            affiche_avis($conn);
        ?>
        <br>
        <br>
        <a href="accueil.php">retour a l'acueille</a>
    </body>
</html>