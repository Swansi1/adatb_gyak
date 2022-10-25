<?php 

if(!isset($_SESSION)) { 
    session_start(); 
} 


?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Kezdőlap</title>

    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    
</head>

<body>
    <?php require("../include/navigation.php");  ?>

    

    
<div class="container mt-5">
    <?php 
        include_once("../connect/connect.php");
        $stmt = $conn->prepare("SELECT faluzenet.datum,faluzenet.datum,faluzenet.szoveg,user.nev FROM `faluzenet` INNER JOIN user ON faluzenet.uid = user.uid ORDER BY `faluzenet`.`datum` DESC"); 
        $stmt->execute(); 
        $uzenoval = $stmt->fetchAll();

        foreach ($uzenoval as $uzenet) {
            echo '<div class="row justify-content-center">
            <div class="card-deck mb-3 text-center">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal">'. $uzenet["datum"] .' - '. $uzenet["nev"] .'</h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mt-3 mb-4">
                        <li>'. $uzenet["szoveg"] .'</li>
                    </ul>
                </div>
            </div>
        </div>
        </div>';
        }
    ?>
    
        
  

    <?php require("../include/footer.html"); ?>
</body>

</html>

