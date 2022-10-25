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
    <title>BestBank | Saját profil</title>
</head>

<body>
    <?php include("../include/navigation.php");   

        include_once("../connect/connect.php");

        
        // var_dump($user);

        if(count($_POST) > 0){
            // var_dump($_POST);
            if($_POST["password"] == ""){
                // hA JELSZÓT akar változtatni
                $stmt = $conn->prepare("UPDATE `user` SET `nev` = :nev, `szuldatum` = :szuldatum, `email` = :email, `telefonszam` = :telefon, `lakcim` = :lakcim, `szemszam` = :szemszam, `jelszo` = :jelszo WHERE `user`.`uid` = :kinel");
                $stmt->execute([
                    "nev" => $_POST["nev"],
                    "szuldatum" => $_POST["szuldatum"],
                    "email" => $_POST["email"],
                    "telefon" => $_POST["telefonszam"],
                    "lakcim" => $_POST["lakcim"],
                    "szemszam" => $_POST["szemszam"],
                    "jelszo" => password_hash($_POST["password"], PASSWORD_DEFAULT),
                    "kinel" => $_SESSION["uid"]
                ]);
            }else{

                //nev,szuldatum,email,telefonszam,lakcim,szemszam
                $stmt = $conn->prepare("UPDATE `user` SET `nev` = :nev, `szuldatum` = :szuldatum, `email` = :email, `telefonszam` = :telefon, `lakcim` = :lakcim, `szemszam` = :szemszam WHERE `user`.`uid` = :kinel");
                $stmt->execute([
                    "nev" => $_POST["nev"],
                    "szuldatum" => $_POST["szuldatum"],
                    "email" => $_POST["email"],
                    "telefon" => $_POST["telefonszam"],
                    "lakcim" => $_POST["lakcim"],
                    "szemszam" => $_POST["szemszam"],
                    "kinel" => $_SESSION["uid"]
                ]);
            }
            $successMessage = "Sikeresen módosítottad az adataidat!";
    }
    $stmt = $conn->prepare("SELECT nev,szuldatum,email,telefonszam,lakcim,szemszam FROM `user`  WHERE uid = :kinel"); // ha nem hacker az emberünk akkor mindenféleképpen kell ilyen accountnak lennie
    $stmt->execute(["kinel" => $_SESSION["uid"]]); 
    $user = $stmt->fetch();
        ?>

<div class="container mt-5">
        <div class="card-deck text-center col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal">Saját profilom</h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mt-3 mb-4">
                        <li>
                            <h4 class="text-danger"><?php echo $errorMsg; ?></h4>
                            <h4 class="text-success"><?php echo $successMessage; ?></h4>
                            <form method="post">
                                <div class="row font-weight-bold">
                                    <div class="col-md-6">Adat</div>
                                    <div class="col-md-6">Érték</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">Teljes neve</div>
                                    <div class="col-md-6"><input type="text" name="nev" class="form-control" value="<?php echo $user["nev"] ?>"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">Születési Dátum</div>
                                    <div class="col-md-6"><input type="date" name="szuldatum" class="form-control" value="<?php echo $user["szuldatum"] ?>"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">Email</div>
                                    <div class="col-md-6"><input type="email" name="email" class="form-control" value="<?php echo $user["email"] ?>"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">Telefonszám</div>
                                    <div class="col-md-6"><input type="tel" name="telefonszam" class="form-control" value="<?php echo $user["telefonszam"] ?>"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">Lakcím</div>
                                    <div class="col-md-6"><input type="text" name="lakcim" class="form-control" value="<?php echo $user["lakcim"] ?>"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">Személyigazolvány szám</div>
                                    <div class="col-md-6"><input type="text" name="szemszam" class="form-control" value="<?php echo $user["szemszam"] ?>"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">Új jelszó megadása</div>
                                    <div class="col-md-6"><input type="password" name="password" class="form-control"></div>
                                </div>
                                <input type="submit" class="btn btn-block btn-primary mt-4" value="Adatok módosítása">
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <?php include("../include/footer.html"); ?>

</body>

</html>