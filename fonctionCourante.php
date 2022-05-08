<?php

//boolean pour savoir si l'utilisateur est admin
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

//met a jour la note moyenne d'un pays d'apres la table des notes
function majMoyenne($conn, $articleId){

    $demandeMoyenne = "SELECT avg(note) FROM avis WHERE article='$articleId'";
    $moyenne = $conn->query($demandeMoyenne);
    $moyenne = $moyenne->fetch_array()[0]; 
    
    $ajoutDansArticle="UPDATE articles SET note = '$moyenne' WHERE id='$articleId'";
    $conn->query($ajoutDansArticle);
}

function ajouteNote($conn, $note, $article, $id){

    //on regarde si une note est deja presente
    $avisEstPtresent = "SELECT * FROM avis WHERE article ='$article' and utilisateur='$id' ";
    $avisEstPtresent = $conn->query($avisEstPtresent);
    $nombreDAvis= mysqli_num_rows($avisEstPtresent);
    
    //si il y en a deja une, on la met a jour 
    if($nombreDAvis>=1){
        $resultatNote = $conn->prepare("UPDATE avis SET note = '$note' WHERE article='$article' and utilisateur='$id'");
        if(!$resultatNote->execute()){
            echo "error";
        }
        majMoyenne($conn, $article);
    }
    //sinon on ajoute
    else{
        $resultatNote = $conn->prepare("INSERT INTO avis (note ,article, utilisateur) VALUE(?, ?, ?)");
        $resultatNote->bind_param('sss',$note,$article,$id);
        if(!$resultatNote->execute()){
            echo "error";
        }
        majMoyenne($conn, $article);
    }
}

?>