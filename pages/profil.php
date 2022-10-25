<?php 
if(!isset($_SESSION)) { 
    session_start(); 
} 
if(!isset($_SESSION["username"])){
    header("Location: /index.php"); // ne tudja acceselni az oldalt ha már be van lépve 
} 

$errors = "";
$successMessage = "";

if(isset($_POST["profiltorles"])){
    //profil törlés
    include_once("../connect/connect.php");
    $stmt = $conn->prepare("DELETE FROM `accounts` WHERE `accounts`.`id` = :uids");
    $stmt->execute(["uids" => $_SESSION["uid"]]); 
    $stmt = $conn->prepare("DELETE FROM `kosar` WHERE `kosar`.`kinel` = :uids");
    $stmt->execute(["uids" => $_SESSION["uid"]]); 
    $stmt = $conn->prepare("DELETE FROM `rendelesek` WHERE `ki` = :uids");
    $stmt->execute(["uids" => $_SESSION["uid"]]); 
    $stmt = $conn->prepare("DELETE FROM `message` WHERE `kitol` = :uids OR `kinek` = :uids");
    $stmt->execute(["uids" => $_SESSION["uid"]]); 
    header("Location: /logout.php");

}


if(isset($_POST["vnev"]) && isset($_POST["knev"]) && isset($_POST["email"]) && isset($_POST["szulido"]) && isset($_POST["telszam"]) && isset($_POST["password"]) && isset($_POST["bemutatkozas"]) && isset($_POST["kedvpizza"]) && isset($_POST["lakcim"])){
    // módosítani akarja a cuccait
    // print_r($_POST);
    include_once("../connect/connect.php");
    $vnev = $_POST["vnev"];
    $knev = $_POST["knev"];
    $email = $_POST["email"];
    $szulido = $_POST["szulido"];
    $telszam = $_POST["telszam"];
    $password = $_POST["password"];
    $bemutatkozas = $_POST["bemutatkozas"];
    $kedvpizza = $_POST["kedvpizza"];
    $lakcim = $_POST["lakcim"];

    $vnecCheck = $_POST["vnevCheck"];
    $knevCheck = $_POST["knevCheck"];
    $emailCheck = $_POST["emailCheck"];
    $telszamCheck = $_POST["telszamCheck"];
    $kedvpizzaCheck = $_POST["kedvpizzaCheck"];
    $bemutatkozasCheck = $_POST["bemutatkozasCheck"];
    $lakcimCheck = $_POST["lakcimCheck"];
    $szuletesiCheck = $_POST["szuletesiCheck"];

    $publicData = [];
    if($emailCheck != NULL){
        $publicData[] = "email";
    }
    if($knevCheck != NULL){
        $publicData[] = "keresztnev";
    }
    if($vnecCheck != NULL){
        $publicData[] = "vezeteknev";
    }
    if($telszamCheck != NULL){
        $publicData[] = "telefonszam";
    }
    if($kedvpizzaCheck != NULL){
        $publicData[] = "kedvenc_pizza";
    }
    if($bemutatkozasCheck != NULL){
        $publicData[] = "bemutatkozas";
    }
    if($lakcimCheck != NULL){
        $publicData[] = "lakcim";
    }
    if($szuletesiCheck != NULL){
        $publicData[] = "birth";
    }

    $stmt = $conn->prepare("UPDATE `accounts` SET `publicData` = :adat WHERE `accounts`.`id` = :uids");
    $stmt->execute(["adat" => serialize($publicData), "uids" => $_SESSION["uid"]]); 


    if(trim($vnev) != ""){
        $stmt = $conn->prepare("UPDATE `accounts` SET `vezeteknev` = :adat WHERE `accounts`.`id` = :uids");
        $stmt->execute(["adat" => $vnev, "uids" => $_SESSION["uid"]]); 
    }

    if(trim($knev) != ""){
        $stmt = $conn->prepare("UPDATE `accounts` SET `keresztnev` = :adat WHERE `accounts`.`id` = :uids");
        $stmt->execute(["adat" => $knev, "uids" => $_SESSION["uid"]]); 
    }

    if(trim($email) != ""){
        if(strpos($email, '@') && strpos($email, '.')){
            $stmt = $conn->prepare("UPDATE `accounts` SET `email` = :adat WHERE `accounts`.`id` = :uids");
            $stmt->execute(["adat" => $email, "uids" => $_SESSION["uid"]]); 
            $_SESSION["email"] = $email;
        }else{
            $errors .= "Nem megfelelelő email!<br>";
        }
    }

    if(trim($szulido) != ""){
        $date_now = new DateTime("now");
        $szuildo_date = new DateTime($szulido);
        $interval = date_diff($date_now, $szuildo_date);
        if($interval->y > 18){
            $stmt = $conn->prepare("UPDATE `accounts` SET `birth` = :adat WHERE `accounts`.`id` = :uids");
            $stmt->execute(["adat" => $szulido, "uids" => $_SESSION["uid"]]); 
        }else{
            $errors .= "Nem vagy 18 éves!<br>";
        }
    }

    if(trim($telszam) != ""){
        $stmt = $conn->prepare("UPDATE `accounts` SET `telefonszam` = :adat WHERE `accounts`.`id` = :uids");
        $stmt->execute(["adat" => $telszam, "uids" => $_SESSION["uid"]]); 
    }
    if(trim($kedvpizza) != ""){
        $stmt = $conn->prepare("UPDATE `accounts` SET `kedvenc_pizza` = :adat WHERE `accounts`.`id` = :uids");
        $stmt->execute(["adat" => $kedvpizza, "uids" => $_SESSION["uid"]]); 
    }
    if(trim($bemutatkozas) != ""){
        $stmt = $conn->prepare("UPDATE `accounts` SET `bemutatkozas` = :adat WHERE `accounts`.`id` = :uids");
        $stmt->execute(["adat" => $bemutatkozas, "uids" => $_SESSION["uid"]]); 
    }
    if(trim($lakcim) != ""){
        $stmt = $conn->prepare("UPDATE `accounts` SET `lakcim` = :adat WHERE `accounts`.`id` = :uids");
        $stmt->execute(["adat" => $lakcim, "uids" => $_SESSION["uid"]]); 
    }
    if(trim($password) != ""){
        $stmt = $conn->prepare("UPDATE `accounts` SET `password` = :adat WHERE `accounts`.`id` = :uids");
        $stmt->execute(["adat" => password_hash($password, PASSWORD_DEFAULT), "uids" => $_SESSION["uid"]]); 
    }

    if(trim($errors) == ""){
        $successMessage = "Sikeresen módosítottad az adataidat!";
    }
}

?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzabajnok Saját profil</title>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <script src="../scripts/jquery.js"></script>
    <link rel="stylesheet" href="../css/mindenes.css">

</head>

<body>
    <?php include("../include/navigation.php");   

            include_once("../connect/connect.php");

            $stmt = $conn->prepare("SELECT * FROM `accounts`  WHERE id = :kinel"); // ha nem hacker az emberünk akkor mindenféleképpen kell ilyen accountnak lennie
            $stmt->execute(["kinel" => $_SESSION["uid"]]); 
            $user = $stmt->fetch();
        ?>

    <main id="container">
        <h1>Felhasználói profilom</h1>
        <form action="">
            <fieldset>
                <legend>Saját adatok</legend>
                <div class="row">
                    <div class="col-25">
                        <label for="telszam">Vezetéknév</label>
                    </div>
                    <div class="col-60">
                        <p><?php print($user["vezeteknev"]); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="telszam">Keresztnév</label>
                    </div>
                    <div class="col-60">
                        <p><?php print($user["keresztnev"]); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="telszam">email cím</label>
                    </div>
                    <div class="col-60">
                        <p><?php print($user["email"]); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="telszam">Telefonszám</label>
                    </div>
                    <div class="col-60">
                        <p><?php print($user["telefonszam"]); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="telszam">Születési dátum</label>
                    </div>
                    <div class="col-60">
                        <p><?php print($user["birth"]); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="bemutatkozas">Bemutatkozás</label>
                    </div>
                    <div class="col-60">
                        <p><?php print($user["bemutatkozas"]); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="kedvpizza">Kedvenc pizzáid</label>
                    </div>
                    <div class="col-60">
                        <p><?php print($user["kedvenc_pizza"]); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="lakcim">Lakcímed (Település, utca, házszám)</label>
                    </div>
                    <div class="col-60">
                        <p><?php print($user["lakcim"]); ?></p>
                    </div>
                </div>
            </fieldset>
        </form>

        <form action="profil.php" method="POST">
            <fieldset>
                <legend>Adatok módosítása</legend>
                <h3>Hagyd üresen azokat a mezőket amiket nem akarsz szerkeszteni!</h3>
                <h2 style="color:red;"><?php echo $errors; ?></h2>
                <h2 style="color:green;"><?php echo $successMessage; ?></h2>
                <div class="row">
                    <div class="col-25">
                        <label for="vnev">Vezetéknév</label>
                    </div>
                    <div class="col-60">
                        <input type="text" id="vnev" name="vnev" placeholder="Vezetékneved...">
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="knev">Keresztnév</label>
                    </div>
                    <div class="col-60">
                        <input type="text" id="knev" name="knev" placeholder="Keresztneved..">
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="email">Email cím</label>
                    </div>
                    <div class="col-60">
                        <input type="email" id="email" name="email" placeholder="Email címed..">
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="password">Jelszó</label>
                    </div>
                    <div class="col-60">
                        <input type="password" id="password" name="password" placeholder="Jelszavad">
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="telszam">Telefonszám</label>
                    </div>
                    <div class="col-60">
                        <input type="tel" id="telszam" name="telszam" placeholder="Telefonszám..">
                    </div>
                </div>

                <div class="row">
                    <div class="col-25">
                        <label for="kedvpizza">Kedvenc pizzáid</label>
                    </div>
                    <div class="col-60">
                        <textarea id="kedvpizza" name="kedvpizza" placeholder="Írj valamit.."></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-25">
                        <label for="bemutatkozas">Bemutatkozás</label>
                    </div>
                    <div class="col-60">
                        <textarea id="bemutatkozas" name="bemutatkozas" placeholder="Írj valamit.."></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-25">
                        <label for="lakcim">Település, utca, házszám</label>
                    </div>
                    <div class="col-60">
                        <input type="text" id="lakcim" name="lakcim" placeholder="Utca, házszám..">
                    </div>
                </div>

                <div class="row">
                    <div class="col-25">
                        <label for="birth">Születési idő</label>
                    </div>
                    <div class="col-60">
                        <input type="date" id="birth" name="szulido" placeholder="Írj valamit..">
                    </div>
                </div>
                <div class="row">
                    <h4>Válaszd ki hogy milyen adatok legyenek publikusak</h4>
                    <h5>(Amiket nem jelölsz be azok nem lesznek publikusak!)</h5>
                </div>
                <div class="row">
                    <input type="checkbox" id="vnevCheck" name="vnevCheck">
                    <label for="vnevCheck"> Vezetéknév</label>
                    <br>
                    <input type="checkbox" id="knevCheck" name="knevCheck">
                    <label for="knevCheck"> Keresztnév</label>
                    <br>
                    <input type="checkbox" id="emailCheck" name="emailCheck">
                    <label for="emailCheck"> Email</label>
                    <br>
                    <input type="checkbox" id="telszamCheck" name="telszamCheck">
                    <label for="telszamCheck"> Telefonszám</label>
                    <br>
                    <input type="checkbox" id="kedvpizzaCheck" name="kedvpizzaCheck">
                    <label for="kedvpizzaCheck"> Kedvenc pizzáid</label>
                    <br>
                    <input type="checkbox" id="bemutatkozasCheck" name="bemutatkozasCheck">
                    <label for="bemutatkozasCheck"> Bemutatkozás</label>
                    <br>
                    <input type="checkbox" id="lakcimCheck" name="lakcimCheck">
                    <label for="lakcimCheck"> Lakcím</label>
                    <br>
                    <input type="checkbox" id="szuletesiCheck" name="szuletesiCheck">
                    <label for="szuletesiCheck"> Születési dátum</label>
                </div>
                <div class="row">
                <input type="submit" value="Módosítás">
                </div>
            </fieldset>            
        </form>

        <form action="profil.php" method="POST" style="padding-bottom: 50px">
            <input type="hidden" name="profiltorles">
            <input type="submit" style="background-color:red;" value="Profil törlése">
        </form>
    </main>

    <?php include("../include/footer.html"); ?>

</body>

</html>