<?php

function estAdmin($conn){
    $id=$_SESSION['id'];
    $recherche="SELECT * FROM utilisateurs WHERE id='$id'";
    $resultat=$conn->query($recherche);
    $resultat = $resultat->fetch_assoc();
    if($resultat["admin"]==1){
        $_SESSION['admin']=true;
        return true;
    }
    else{
        return false;
    }
}

function majMoyenne($conn, $articleId){


    
        $demandeMoyenne = "SELECT avg(note) FROM avis WHERE article='$articleId'";
        $moyenne = $conn->query($demandeMoyenne);
        $moyenne = $moyenne->fetch_array()[0]; 
    
    $ajoutDansArticle="UPDATE articles SET note = '$moyenne' WHERE id='$articleId'";
    $conn->query($ajoutDansArticle);
    

    
}

?>