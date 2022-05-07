<?php
    // Initialiser la session
    session_start();

    include("mysql.php");
    include("fonctionCourante.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id=$_SESSION['id'];
        $articleId=$_POST['article'];
        $note = $_POST['note'];

        $avisEstPtresent = "SELECT * FROM avis WHERE article ='$articleId' and utilisateur='$id' ";
        $avisEstPtresent = $conn->query($avisEstPtresent);
        $nombreDAvis= mysqli_num_rows($avisEstPtresent);
        
        if($nombreDAvis>=1){
            $resultatNote = $conn->prepare("UPDATE avis SET note = '$note' WHERE article='$articleId' and utilisateur='$id'");
            if(!$resultatNote->execute()){
                echo "error";
            }
            
            majMoyenne($conn, $articleId);
        }
        else{
            $resultatNote = $conn->prepare("INSERT INTO avis (note ,article, utilisateur) VALUE(?, ?, ?)");
            $resultatNote->bind_param('sss',$_POST["note"],$_POST["article"],$_SESSION["id"]);
            if(!$resultatNote->execute()){
                echo "error";
            }
            majMoyenne($conn, $articleId);
        }        
    }

    

    function pageAcceuilleAdmin($conn){
        echo " <a href=\"pageAdmin.php\">page admin</a>";
        echo "<br>";
        echo "<br>";
    }

    function pageUtilisateurConnecte($conn){
        echo "<a href=\"monProfile.php\">mon profil</a><br><br><a href=\"deconnexion.php\">deconnexion</a><br><br>";
        if(estAdmin($conn)){
            pageAcceuilleAdmin($conn);
        }
        echo " recherche : 
            <form action =\"\" method=\"GET\">
                <input type=\"text\" name=\"recherche\">     
                <input type=\"submit\">  
                <br>
                <input type=\"number\" name=\"minimum\" max=\"5\" min=\"0\"> : minimum  
                <input type=\"number\" name=\"maximum\" max=\"5\" min=\"0\"> : maximum 
                
            </form>
        ";
        recherche($conn);
    }

    function recherche($conn){
        if(isset($_GET['recherche'])){
            $recherche="%".htmlspecialchars($_GET['recherche'])."%";
        }
        else{
            $recherche="%";
        }
        if( isset($_GET['minimum']) && !empty($_GET['minimum'])){
            $min=$_GET['minimum'];
        }
        else{
            $min=0;
        }
        if(isset($_GET['maximum']) && !empty($_GET['maximum'])){
            $max=$_GET['maximum'];
            
        }
        else{
            $max=5;
        }
        $demande = "SELECT * FROM articles WHERE titre LIKE '$recherche' AND note>='$min' AND note<='$max'";
        $resultat = $conn->query($demande);
        affichageRecherche($resultat, $conn);
    }

    function affichageRecherche($resultat, $conn){
        while ($article = $resultat->fetch_assoc()){
            $id = $article["id"];
            $demandeMoyenne = "SELECT note FROM articles WHERE id='$id'";
            $moyenne = $conn->query($demandeMoyenne);
            $moyenne = $moyenne->fetch_array()[0];
            echo "<br>
                <br>".
                $article['titre'].":
                <br>".
                $article['texte']."
                <br>
                note moyenne : ".$moyenne."
                <form method=\"POST\" action=\"\">
                <input type=\"number\" min=\"0\" max=\"5\" name=\"note\">
                <input type=\"hidden\" name=\"article\" value=\"".$article['id']."\">
                <input type=\"submit\">
                </form>
                <br>
                <a href=\"page_article.php?article=".$id."\">voir l'article</a>"

            ;
        }
    }

    function pageUtilisateurInconnu(){
        echo "Connectez vous pour voire les articles
            <br>
            <br>
            <a href=\"connexion.php\">connexion</a>
            <br>
            <a href=\"inscription.php\">inscription</a>";
    }

    // Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
    
?>
<!DOCTYPE html>
<html>
    <body>
        <h1>Site de notation</h1>
        <br>
        <?php 
            if(isset($_SESSION["pseudo"])){
                pageUtilisateurConnecte($conn); 
            }   
            else{
                pageUtilisateurInconnu();
            }
        ?>
    </body>
</html>