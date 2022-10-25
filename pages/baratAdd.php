<?php 
if(!isset($_SESSION)) { 
    session_start(); 
} 
if(!isset($_SESSION["username"])){
    header("Location: /index.php");
} 

$errorMsg = "";
$successMessage = "";

?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BestBank | Barát hozzáadása</title>
</head>

<body>
    <?php include("../include/navigation.php");   

        include_once("../connect/connect.php");

        
        // var_dump($user);
    if(isset($_GET["rm"])){
        $oldFId = $_GET["rm"]; // törlő id
        $stmt = $conn->prepare("DELETE FROM `ismerose` WHERE kinek = :kinek AND ki = :ki");
        $stmt->execute([
            "kinek" => $_SESSION["uid"],
            "ki" => $oldFId
        ]); 
        $successMessage = "Sikeres törölted a barátodat!";
    }

    if(count($_POST) > 0){
        // var_dump($_POST);
        if(isset($_POST["femail"])){
            // barát hozzáadása
            $stmt = $conn->prepare("SELECT user.uid  FROM `user` WHERE `email` = :email");
            $stmt->execute(["email" => $_POST["femail"]]); 
            $isExist = $stmt->fetch();
            var_dump($isExist);
            if($isExist != NULL){
                // ha van ilyen felhasználó 
                $stmt = $conn->prepare("SELECT kinek  FROM `ismerose` WHERE `ki` = :ki AND `kinek` = :kinek");
                $stmt->execute([
                    "ki" => $isExist["uid"],
                    "kinek" => $_SESSION["uid"]
                ]); 
                $isBarat = $stmt->fetch();
                if(!$isBarat){
                    $stmt = $conn->prepare("INSERT INTO `ismerose` (`ki`, `kinek`) VALUES (:ki, :kinek)");
                    $stmt->execute([
                        "ki" => $isExist["uid"],
                        "kinek" => $_SESSION["uid"]
                    ]); 
                    $successMessage = "Sikeres barátfelvétel!";
                }else{
                    $errorMsg = "Már a barátod!";
                }
                
                
            }else{
                $errorMsg = "Nincs ilyen regisztrált felhasznló!";
            }
        }
    }
    $stmt = $conn->prepare("SELECT user.email,user.nev,user.uid  FROM `ismerose` INNER JOIN user ON ismerose.ki = user.uid WHERE `kinek` = :kinel");
    $stmt->execute(["kinel" => $_SESSION["uid"]]); 
    $ismerosok = $stmt->fetchAll();
    // var_dump($ismerosok);
?>

    <div class="container mt-5">
        <div class="card-deck text-center">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal">Barát hozzáadása</h4>
                </div>
                <div class="card-body">

                            <h4 class="text-danger"><?php echo $errorMsg; ?></h4>
                            <h4 class="text-success"><?php echo $successMessage; ?></h4>
                            <div class="container mb-4">
                                <form action="" method="post">
                                    <div class="row justify-content-center">
                                        <div class="col-md-6 font-weight-bold">Barát email címe:</div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-md-6"><input type="text" class="form-group" name="femail"></div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-md-6"><input type="submit" class="btn btn-block btn-success" value="Barát hozzáadása"></div>
                                    </div>
                                </form>
                            </div>

                            <h4>Jelenlegi Barátok:</h4>
                            <div class="container col-md-6">
                                <div class="row">
                                    <div class="col-md-6">Email</div>
                                    <div class="col-md-6">Művelet</div>
                                </div>
                                <?php
                                    foreach ($ismerosok as $ismeros) {
                                        echo '<div class="row">
                                        <div class="col-md-6">'. $ismeros["nev"] .' ('. $ismeros["email"] .')</div>
                                        <div class="col-md-6"><a href="/pages/baratAdd.php?rm='.$ismeros["uid"].'" class="btn btn-danger">Törlés</a></div>
                                    </div>';
                                    }
                                ?>
                            </div>

                </div>
            </div>
        </div>
    </div>

    <?php include("../include/footer.html"); ?>

</body>

</html>