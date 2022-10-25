<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BestBank | Utalás</title>

    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
</head>

<body>
    <?php require("../include/navigation.php");  ?>

    <?php
    //TODO Lehet minuszba utalni!! (DE ez egy future)
    $friendEmail = "";
    if(isset($_GET["ismid"])){
        // gyors utalás
        require_once("../connect/connect.php"); // mysql meghívás
        $stmt = $conn->prepare("SELECT email FROM `user` WHERE `uid` LIKE :uid");
        $stmt->execute(["uid" => $_GET["ismid"]]); 
        $friendEmail = $stmt->fetch()[0];
    }

    $errorMsg = "";
    if(count($_POST) > 0){
        $kinek = $_POST["kinek"]; // email
        $mennyit = $_POST["mennyit"];
        $uzenet = $_POST["uzenet"];

        require_once("../connect/connect.php");
        $stmt = $conn->prepare("SELECT penz FROM `user` WHERE `uid` LIKE :uid");
        $stmt->execute(["uid" => $_SESSION["uid"]]); 
        $sajatPenz = $stmt->fetch()[0];

        $mennyiMarad = $sajatPenz - $mennyit;
        
        if($mennyiMarad >= 0 && $mennyit > 0){
            // tud utalni mivel van elég pénze

            $stmt = $conn->prepare("SELECT uid FROM `user` WHERE `email` LIKE :email");
            $stmt->execute(["email" => $kinek]); 
            $friendId = $stmt->fetch(); // Akinek utal az IDJe kell nekünk
            if($friendId == NULL){
                $errorMsg .= "Nincs ilyen felhasználó akinek tudnál utalni!<br>";
            }else{
                $stmt = $conn->prepare("UPDATE `user` SET `penz` = `penz` + :mennyit WHERE `user`.`email` = :kinek");
                $stmt->execute([
                    "mennyit" => $mennyit,
                    "kinek" => $kinek
                ]);  // hozzáadja a pénzt akinek utaltunk
    
                $stmt = $conn->prepare("UPDATE `user` SET `penz` = `penz` - :mennyit WHERE `user`.`uid` = :uid");
                $stmt->execute([
                    "mennyit" => $mennyit,
                    "uid" => $_SESSION["uid"]
                ]);  // Levonja a saját pénzünket
    
                $stmt = $conn->prepare("INSERT INTO `utalas` ( `szoveg`, `osszeg`, `ki`, `kinek`) VALUES (:szoveg, :osszeg, :ki, :kinek)");
                $stmt->execute([
                    "szoveg" => $uzenet,
                    "osszeg" => $mennyit,
                    "ki" => $_SESSION["uid"],
                    "kinek" => $friendId[0]
                ]); // utalás log
            }

        }else{
            $errorMsg .= "Nincs elég pénzed az utalás megkezdéséhez vagy rossz számot írtál be!<br>";
        }
    }

    // var_dump($_POST);
    ?>

    <div class="container mt-5">

        <div class="card-deck text-center col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal">Új utalás indítása</h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mt-3 mb-4">
                        <li>
                            <h4 class="text-danger"><?php echo $errorMsg; ?></h4>
                            <form method="post">
                                <div class="row font-weight-bold">
                                    <div class="col-md-4">Kinek(Email cím)</div>
                                    <div class="col-md-4">Mennyit</div>
                                    <div class="col-md-4">Üzenet</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4"><input type="text" name="kinek" value="<?php echo $friendEmail; ?>" required></div>
                                    <div class="col-md-4"><input type="number" name="mennyit" required></div>
                                    <div class="col-md-4"><input type="text" name="uzenet"></div>
                                </div>
                                <input type="submit" class="btn btn-block btn-primary mt-4" value="Utalás">
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <?php require("../include/footer.html"); ?>


</body>

</html>