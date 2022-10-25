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
                        <h4 class="my-0 font-weight-normal">Új utalás indítása ismerősnek</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>
                                <div class="row font-weight-bold">
                                    <div class="col-md-6">Név</div>
                                    <div class="col-md-6">Utalás</div>
                                </div>
                                <?php 
                                    $stmt = $conn->prepare("SELECT user.nev,user.uid  FROM `ismerose` INNER JOIN user ON ismerose.kinek = user.uid WHERE `ki` = :uid"); 
                                    $stmt->execute(["uid" => $_SESSION["uid"]]); 
                                    $ismerosok = $stmt->fetchAll();
                                    if(count($ismerosok) == 0){
                                        print('<div class="row">
                                        <div class="col-md-12 font-weight-bold">Nincsen ismerősöd :(</div>
                                    </div');
                                    }
                                    foreach ($ismerosok as $ismeros) {
                                        print('<div class="row">
                                        <div class="col-md-6 font-weight-bold">'. $ismeros["nev"] .'</div>
                                        <div class="col-md-6"><a class="btn btn-success" href="/pages/utalas.php?ismid='. $ismeros["uid"] .'">Utalás</a></div>
                                    </div>');
                                    }
                                ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="row justify-content-center">
        <div class="card-deck text-center col-md-6">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">Számlatörténet</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>
                                <h4>Kimenő utalások:</h4>
                                <div class="row font-weight-bold">
                                    <div class="col-md-3">Dátum</div>
                                    <div class="col-md-3">Kinek</div>
                                    <div class="col-md-3">Mennyit</div>
                                    <div class="col-md-3">Üzenet</div>
                                </div>
                                <?php 
                                    $stmt = $conn->prepare("SELECT utalas.datum,utalas.szoveg,utalas.osszeg,user.nev  FROM `utalas` INNER JOIN user ON utalas.kinek = user.uid WHERE `ki` = :uid ORDER BY utalas.datum DESC"); 
                                    $stmt->execute(["uid" => $_SESSION["uid"]]); 
                                    $utalasok = $stmt->fetchAll();
                                    if(count($utalasok) == 0){
                                        print('<div class="row">
                                        <div class="col-md-12 font-weight-bold">Nincs kimenő utalásod :(</div>
                                    </div');
                                    }
                                    foreach ($utalasok as $utalas) {
                                        print('<div class="row">
                                        <div class="col-md-3 font-weight-bold">'. $utalas["datum"] .'</div>
                                        <div class="col-md-3">'. $utalas["nev"] .'</div>
                                        <div class="col-md-3 text-danger">-'. $utalas["osszeg"] .' Ft</div>
                                        <div class="col-md-3">'. $utalas["szoveg"] .'</div>
                                    </div>');
                                    }
                                ?>
                            </li>
                            <li class="mt-5">
                                <h4>Bejövő utalások:</h4>
                                <div class="row font-weight-bold">
                                    <div class="col-md-3">Dátum</div>
                                    <div class="col-md-3">Ki</div>
                                    <div class="col-md-3">Mennyit</div>
                                    <div class="col-md-3">Üzenet</div>
                                </div>
                                <?php 
                                    $stmt = $conn->prepare("SELECT utalas.datum,utalas.szoveg,utalas.osszeg,user.nev  FROM `utalas` INNER JOIN user ON utalas.ki = user.uid WHERE `kinek` = :uid ORDER BY utalas.datum DESC"); 
                                    $stmt->execute(["uid" => $_SESSION["uid"]]); 
                                    $utalasok = $stmt->fetchAll();
                                    if(count($utalasok) == 0){
                                        print('<div class="row">
                                        <div class="col-md-12 font-weight-bold">Nincs bejövő utalásod :(</div>
                                    </div');
                                    }
                                    foreach ($utalasok as $utalas) {
                                        print('<div class="row">
                                        <div class="col-md-3 font-weight-bold">'. $utalas["datum"] .'</div>
                                        <div class="col-md-3">'. $utalas["nev"] .'</div>
                                        <div class="col-md-3 text-success">+'. $utalas["osszeg"] .' Ft</div>
                                        <div class="col-md-3">'. $utalas["szoveg"] .'</div>
                                    </div>');
                                    }
                                ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
    </div>

    <?php require("../include/footer.html"); ?>
</body>

</html>