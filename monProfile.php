<?php
session_start();

if(!isset($_SESSION["pseudo"])){
    header("Location: acueille.php");
}
    
  

?>


<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Mon profil</title>
</head>
<body>

<h1><?php echo "Profil de ".$_SESSION["pseudo"] ?></h1>

</body>
</html>