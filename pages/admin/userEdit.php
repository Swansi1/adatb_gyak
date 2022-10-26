<?php 
if(!isset($_SESSION)) { 
    session_start(); 
} 
if(!isset($_SESSION["username"])){
    header("Location: /index.php");
} 

$errorMsg = "";
$successMessage = "";

//TODO Felhasználó törlése

?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BestBank | Felhasználó kezelés</title>
</head>

<body>
    <?php 
    include("../../include/navigation.php");   
    include_once("../../connect/connect.php");



    //! GET methodok (Admin hozzáadása, törlése)
    if(isset($_GET["adminAdd"])){
        // admin hozzáadása
        // var_dump($_GET);
        $stmt = $conn->prepare("UPDATE user SET rang = 1 WHERE user.uid = :uid"); 
        $stmt->execute(["uid" => $_GET["adminAdd"]]); 
        $successMessage = "Sikeresen hozzáadtad az adminokhoz a felhasználót!";
    }
    if(isset($_GET["adminRemove"])){
        // admin hozzáadása
        // var_dump($_GET);
        $stmt = $conn->prepare("UPDATE user SET rang = 0 WHERE user.uid = :uid"); 
        $stmt->execute(["uid" => $_GET["adminRemove"]]); 
        $successMessage = "Sikeresen eltávolítottad az adminok közül a felhasználót!";
    }

    $stmt = $conn->prepare("SELECT user.*, COUNT(osszeg) as osszeg, SUM(osszeg) as mennyit FROM `utalas` INNER JOIN user ON utalas.ki = user.uid GROUP BY utalas.ki"); // TODO ha nem utalt még nem fog bele kerülni a táblába  xd 
    $stmt->execute(); 
    $alluser = $stmt->fetchAll();

    $helperArr = [ // adatokat ne kelljen külön külön begépelni hanem behelyetesíti
        "nev" => "Név",
        "szuldatum" => "Születési Dátum",
        "email" => "Email cím",
        "telefonszam" => "Telefonszám",
        "lakcim" => "Lakcím",
        "szemszam" => "Személyigazolvány Szám",
        "penz" => "Jelenlegi Pénz",
        "rang" => "Jogosultsági Szint",
        "mennyit" => "Mennyit utalt",
        "osszeg" => "Utalások összege"
    ];
?>

    <div class="container mt-5">
        <div class="card-deck text-center">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal">Összes felhasználók</h4>
                </div>
                <div class="card-body">
                    <h4 class="text-success"><?php echo $successMessage; ?></h4>
                    <?php 
                        foreach ($alluser as $ki) {
                            $adatok = '';
                            $btns = "";
                            foreach ($helperArr as $k => $v) {
                                if($k == "rang"){
                                    $crole = $ki[$k] == 0 ? "Felhasználó" : "Admin";
                                    $adatok .= '<div class="row">
                                    <div class="col-md-6">'. $v .'</div>
                                    <div class="col-md-6">'. $crole .'</div>
                                    </div>';
                                    if($ki[$k] == 0){
                                        // sima felhasználó, adminnak kinevezés btn
                                        $btns .= '<div class="row">
                                                <div class="col-md-12"><a href="/pages/admin/userEdit.php?adminAdd='.$ki["uid"].'" class="btn btn-success">Felhasználó kinevezése Adminnak</a></div>
                                                </div>';
                                    }else{
                                        $btns .= '<div class="row">
                                                <div class="col-md-12"><a href="/pages/admin/userEdit.php?adminRemove='.$ki["uid"].'" class="btn btn-danger">Admin eltávolítása</a></div>
                                                </div>';
                                    }
                                }else{
                                    $adatok .= '<div class="row">
                                    <div class="col-md-6">'. $v .'</div>
                                    <div class="col-md-6">'. $ki[$k] .'</div>
                                    </div>';
                                }
                            }
                            $adatok .= $btns;
                            // var_dump($ki);
                            print('
                            <p>
                            <button class="btn btn-primary" type="button" data-toggle="collapse"
                                data-target="#collapse'.$ki["uid"].'" aria-expanded="false" aria-controls="collapse'.$ki["uid"].'" data-search="'. $ki["email"] .' '.$ki["nev"].'">
                                '. $ki["email"] .'
                            </button>
                        </p>
                        <div class="collapse" id="collapse'.$ki["uid"].'">
                            <div class="card card-body">
                               <div class="row font-weight-bold">
                                    <div class="col-md-6">Adat</div>
                                    <div class="col-md-6">Érték</div>
                               </div>
                               '. $adatok .'
                            </div>
                        </div>
                            '); 
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php include("../../include/footer.html"); ?>

</body>

</html>