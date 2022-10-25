<?php 
if(!isset($_SESSION)) { 
    session_start(); 
} 
if(!isset($_SESSION["username"])){
    if($_SESSION["admin"] != 1){ 
        header("Location: /index.php"); // ne tudja acceselni az oldalt ha már be van lépve 
    }
} 

include_once("../connect/connect.php");

$errors = "";
$successMessage = "";

if(isset($_POST["letiltas"])){
    $letiltasUser = $_POST["letiltas"];
    $stmt = $conn->prepare("UPDATE `accounts` SET `blocked` = '1' WHERE `accounts`.`id` = :tiltuser");
    $stmt->execute(["tiltuser" => $letiltasUser]); 
}

if(isset($_POST["felold"])){
    $feloldUs = $_POST["felold"];
    $stmt = $conn->prepare("UPDATE `accounts` SET `blocked` = '0' WHERE `accounts`.`id` = :feloldUser");
    $stmt->execute(["feloldUser" => $feloldUs]); 
}

if(isset($_POST["adminadd"])){
    $adminadd = $_POST["adminadd"];
    $stmt = $conn->prepare("UPDATE `accounts` SET `admin` = '1' WHERE `accounts`.`id` = :adminadd");
    $stmt->execute(["adminadd" => $adminadd]); 
}
if(isset($_POST["adminremove"])){
    $adminremove = $_POST["adminremove"];
    $stmt = $conn->prepare("UPDATE `accounts` SET `admin` = '0' WHERE `accounts`.`id` = :adminremove");
    $stmt->execute(["adminremove" => $adminremove]); 
}

?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzabajnok Admin panel</title>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <script src="../scripts/jquery.js"></script>
    <link rel="stylesheet" href="../css/mindenes.css">
    <link rel="stylesheet" href="../css/osszecsukhato.css">

</head>

<body>
    <?php include("../include/navigation.php");  ?>

    <main id="container">
        <h1>Leadott rendelések</h1>
        <table class="tableCenter">
            <tr>
                <th>Ki</th>
                <th>Mikor</th>
                <th>Mit</th>
                <th>Mennyit</th>
                <th>Hova</th>
            </tr>
            <?php
                $stmt = $conn->prepare("SELECT accounts.vezeteknev, accounts.keresztnev, rendelesek.mikor, pizzak.nev,rendelesek.mennyit,accounts.lakcim FROM `rendelesek` INNER JOIN accounts ON rendelesek.ki = accounts.id INNER JOIN pizzak ON rendelesek.mit = pizzak.id;");
                $stmt->execute(); 
                $rendelesek = $stmt->fetchAll();
                foreach ($rendelesek as $rendeles) {
                    print('<tr>
                        <td>'. $rendeles["vezeteknev"] .' '. $rendeles["keresztnev"] .'</td>
                        <td>'. $rendeles["mikor"] .'</td>
                        <td>'. $rendeles["nev"] .'</td>
                        <td>'. $rendeles["mennyit"] .' Db</td>
                        <td>'. $rendeles["lakcim"] .'</td>
                    </tr>');
                }
            ?>
        </table>
        
        <h1>Oldalra regisztrált felhasználók</h1>
        <?php 
            $helyettesitoTomb = [
                "vezeteknev" => "Vezetéknév",
                "keresztnev" => "Keresztnév",
                "email" => "Email cím",
                "telefonszam" => "Telefonszám",
                "kedvenc_pizza" => "Kedvenc pizzái",
                "lakcim" => "Lakcím",
                "birth" => "Születési dátum",
                "blocked" => "Letiltott (0 = nincs letilva | 1 = letiltva)"
            ];
            $stmt = $conn->prepare("SELECT * FROM `accounts`");
            $stmt->execute(); 
            $users = $stmt->fetchAll();
            // $teszt = unserialize($users[0]["publicData"]);
            // var_dump($teszt);
            // var_dump($users[0][$teszt[0]]);
            foreach ($users as $user) {
                $publicData = unserialize($user["publicData"]);
                $kiirandoNev = "Nincs megadva";
                $generateP = "";
                foreach ($helyettesitoTomb as $key => $tomb) {
                    if($kiirandoNev == "Nincs megadva"){
                        if($key == "email"){
                            $kiirandoNev = $user[$key];
                        }
                    }
                    $generateP .= "<p>". $tomb  .": ". $user[$key] ." </p>";
                }
                if($user["admin"] == 0){
                    $generateP .= "<form action='admin.php' method='POST'>
                            <input type='hidden' name='adminadd' value='". $user['id'] ."'>
                            <input style='background-color: green; color:white' type='submit' value='Felhasználó kijelölése adminnak'>
                            </form>";
                }else{
                    $generateP .= "<form action='admin.php' method='POST'>
                            <input type='hidden' name='adminremove' value='". $user['id'] ."'>
                            <input style='background-color: green; color:white' type='submit' value='Admin eltávolítása'>
                            </form>";
                }
                if ($user["blocked"] == 0) {
                    $generateP .= "<form action='admin.php' method='POST'>
                        <input type='hidden' name='letiltas' value='". $user['id'] ."'>
                        <input style='background-color: red' type='submit' value='Felhasználó letiltása'>
                        </form>";
                }else {
                    $generateP .= "<form action='admin.php' method='POST'>
                    <input type='hidden' name='felold' value='". $user['id'] ."'>
                    <input style='background-color: green;color:white;' type='submit' value='Felhasználó engedélyezése'>
                    </form>";
                }
                print('<button class="collapsible">'. $kiirandoNev .'</button>
                <div class="content">'. $generateP .'</div>');
            }
        ?>
    </main>

    <script>
        var coll = $(".collapsible");
        var i;

        for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.maxHeight){
            content.style.maxHeight = null;
            } else {
            content.style.maxHeight = content.scrollHeight + "px";
            } 
        });
        }
</script>
    <?php include("../include/footer.html"); ?>

</body>

</html>