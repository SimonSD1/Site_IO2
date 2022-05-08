<?php
    session_start();
    include("mysql.php");
    include("fonctionCourante.php");

    //supprime un avis et met a jour la moyenne de l'article a qui ont a enlevé une note
    function supprimeAvis($conn, $idAvis, $idArticle){
        $requete="DELETE FROM avis WHERE id = '$idAvis' ";
        $requete= $conn->query($requete);
        majMoyenne($conn, $idArticle);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //si on souhaite supprimer un avis on fait simplement apelle a supprimeAvis
        if(isset($_POST['avis']) && !empty($_POST['avis'])){
            $idAvis=$_POST['avis'];
            $idArticle=$_POST['idArticle'];
            supprimeAvis($conn, $idAvis, $idArticle);
            
        }
        //pour supprimer un utilisateur, on supprime tout ses avis puis on supprime l'utilisateur
        if(isset($_POST['utilisateur']) && !empty($_POST['utilisateur'])){
            $idUtilisateur=$_POST['utilisateur'];
            $rechercheDesAvis="SELECT id, article FROM avis WHERE utilisateur='$idUtilisateur'";
            $rechercheDesAvis = $conn->query($rechercheDesAvis);
            $rechercheDesAvis->fetch_assoc();

            foreach ($rechercheDesAvis as $avis){
                supprimeAvis($conn, $avis['id'], $avis['article']);
            }

            $supprUtilisateur="DELETE FROM utilisateurs WHERE id='$idUtilisateur'";
            $supprUtilisateur=$conn->query($supprUtilisateur);
        }
        
    }

    function pageAdmin($conn){
        $demande = "SELECT avis.note, utilisateurs.pseudo, avis.id, articles.titre, avis.article FROM avis, utilisateurs, articles WHERE avis.utilisateur=utilisateurs.id AND avis.article=articles.id"; 
        $resultat = $conn->query($demande);

        echo "liste des avis <br>";
        // recupere tout les utilisateurs et affiche un bouton supprimer
        while ($aviUtilisateur = $resultat->fetch_assoc()){
            echo $aviUtilisateur['pseudo']." a mis la note de ".$aviUtilisateur['note']." a l'article ".$aviUtilisateur['titre'];
            echo 
            "
                <form method=\"POST\" action=\"\">
                <input type=\"hidden\" name=\"avis\" value=\"".$aviUtilisateur['id']."\">
                <input type=\"hidden\" name=\"idArticle\" value=\"".$aviUtilisateur['article']."\">
                <input type=\"submit\" value=\"supprimer\">
                </form>"
            ;
            echo "<br>";
        }

        $id=$_SESSION['id'];
        $demande = "SELECT pseudo, id FROM utilisateurs WHERE id!='$id'"; 
        $resultat = $conn->query($demande);

        //affiche tout les utilisateur different de l'admin et affiche un bouton supprimer
        echo "liste des utilisateurs <br>";
        while ($utilisateur = $resultat->fetch_assoc()){
            echo $utilisateur['pseudo'];
            echo 
            "
                <form method=\"POST\" action=\"\">
                <input type=\"hidden\" name=\"utilisateur\" value=\"".$utilisateur['id']."\">
                <input type=\"submit\" value=\"supprimer\">
                </form>"
            ;
            echo "<br>";
        }
        echo "<br>
            <br>
            <a href=\"accueil.php\">retour a l'acueille</a>
        ";
    }
?>


<!doctype html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Page admin</title>
    </head>
    <body>

    <h1>Page Admin</h1>

    <?php 
        if(!estAdmin($conn)){
            header("Location: accueil.php");
        }
        else{
            pageAdmin($conn);
        }
    ?>
    </body>
</html>