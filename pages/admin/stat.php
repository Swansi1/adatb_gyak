<?php 
if(!isset($_SESSION)) { 
    session_start(); 
} 
if(!isset($_SESSION["username"])){
    header("Location: /index.php");
} 

$errorMsg = "";
$successMessage = "";


//TODO
// SELECT user.nev, COUNT(osszeg), SUM(osszeg) FROM `utalas` INNER JOIN user ON utalas.ki = user.uid GROUP BY utalas.ki;
// ki mennyit és hányszor utalt


// az adminok mennyit posztoltak
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BestBank | Statisztika</title>
</head>

<body>
    <?php 
    include("../../include/navigation.php");   
    include_once("../../connect/connect.php");

    $stmt = $conn->prepare("SELECT user.nev, COUNT(osszeg) as osszeg, SUM(osszeg) as mennyit FROM `utalas` INNER JOIN user ON utalas.ki = user.uid GROUP BY utalas.ki");
    $stmt->execute(); 
    $kiHanyszorMennyit = $stmt->fetchAll();

    // melyik admin hányszor posztolt
    $stmt = $conn->prepare("SELECT user.nev,user.email, COUNT(*) as hanyszor FROM `faluzenet` INNER JOIN user ON faluzenet.uid = user.uid GROUP BY faluzenet.uid");
    $stmt->execute(); 
    $adminPost = $stmt->fetchAll();
    // var_dump($ismerosok);
?>

    <div class="container mt-5">
        <div class="card-deck text-center">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal">Ki mennyit és hányszor utalt</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">Ki</div>
                        <div class="col-md-4">Mennyit</div>
                        <div class="col-md-4">Hánszor</div>
                    </div>
                    <?php 
                        foreach ($kiHanyszorMennyit as $ki) {
                            print('<div class="row">
                                <div class="col-md-4">'. $ki["nev"] .'</div>
                                <div class="col-md-4">'. $ki["osszeg"] .'</div>
                                <div class="col-md-4">'. $ki["mennyit"] .'</div>
                            </div>'); 
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>


    <div class="container mt-5">
        <div class="card-deck text-center">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal">Adminok hányszor posztoltak</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">Ki</div>
                        <div class="col-md-6">Mennyit</div>
                    </div>
                    <?php 
                        foreach ($adminPost as $ki) {
                            print('<div class="row">
                                <div class="col-md-6">'. $ki["nev"] .' ('. $ki["email"] .')</div>
                                <div class="col-md-6">'. $ki["hanyszor"] .'</div>
                            </div>'); 
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php include("../../include/footer.html"); ?>

</body>

</html>