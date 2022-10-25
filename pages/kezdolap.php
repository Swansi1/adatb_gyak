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
    <?php

    if(!isset($_SESSION["username"])){
        echo "NEM VAGY be lépve";
    }
    include_once("../connect/connect.php");
    
  
    ?>
    MENNYI PÉNZED VAN: 0 Ft

    Utalás másnak
    Előző utalások megtekintése


    <div class="container">
        <div class="row">
            <div class="card-deck text-center col-md-6">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">Jelenlegi egyenleg</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mt-3 mb-4">
                            <li><?php 
                                $stmt = $conn->prepare("SELECT penz FROM `user` WHERE uid = :uid "); 
                                $stmt->execute(["uid" => $_SESSION["uid"]]); 
                                $penz = $stmt->fetch()[0];
                                echo $penz;
                            ?> Ft</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-deck text-center col-md-6">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">Számlatörténet</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>'. $uzenet["szoveg"] .'</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
        <div class="card-deck text-center col-md-6">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">Új utalás indítása ismerősnek</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>'. $uzenet["szoveg"] .'</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require("../include/footer.html"); ?>
</body>

</html>