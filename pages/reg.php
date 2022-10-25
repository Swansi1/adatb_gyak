<?php

if(!isset($_SESSION)) { 
    session_start(); 
} 
if(isset($_SESSION["username"])){
    header("Location: /index.php"); // ne tudja acceselni az oldalt ha már be van lépve 
} 
$errors = "";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // array(7) { ["email"]=> string(11) "asd@asd.com" ["nev"]=> string(3) "asd" ["szuldate"]=> string(10) "2022-10-01" ["phone"]=> string(3) "asd" ["lakcim"]=> string(3) "asd" ["szemszam"]=> string(3) "asd" ["jelszo"]=> string(15) "ukHgrQXsjmawHV7" }

    $nev = $_POST["nev"];
    $email = $_POST["email"];
    $szuldate = $_POST["szuldate"];
    $phone = $_POST["phone"];
    $lakcim = $_POST["lakcim"];
    $szemszam = $_POST["szemszam"];
    $jelszo = $_POST["jelszo"];

    

    if(trim($nev) == ""){
        $errors .= "Nincs megadva név! <br>";
    }

    if(!strpos($email, '@') || !strpos($email, '.')){
        $errors .= "Helytelen email cím!<br>";
    }

    if(trim($lakcim) == ""){
        $errors .= "Nincs megadva lakcím!<br>";
    }
    if(trim($phone) == ""){
        $errors .= "Nem adtál meg telefonszámot!<br>";
    }

    $date_now = new DateTime("now");
    $szuildo_date = new DateTime($szuldate);
    if($szuildo_date > $date_now){
        $errors .= "Még meg sem születtél!<br>";
    }
    $interval = date_diff($date_now, $szuildo_date);
    if($interval->y < 18){
        $errors .= "Nem vagy 18 éves!<br>";
    }


    if($errors == ""){// NINCS HIBA
        // megnézzük hogy szabad-e az email
        require_once("../connect/connect.php"); // mysql meghívás
        $stmt = $conn->prepare("SELECT email FROM `user` WHERE `email` LIKE :email");
        $stmt->execute(["email" => $email]); 
        $isExists = $stmt->fetchAll();
        if(!$isExists){
            $stmt = $conn->prepare("INSERT INTO `user`(`nev`, `szuldatum`, `email`, `telefonszam`, `lakcim`, `szemszam`, `jelszo`) VALUES (:nev, :szuldatum, :email, :telefonszam, :lakcim, :szemszam, :jelszo)");
            $stmt->execute([
                "nev" => $nev,
                "szuldatum" => $szuldate,
                "email" => $email,
                "jelszo" => password_hash($password, PASSWORD_DEFAULT),
                "telefonszam" => $phone,
                "lakcim" => $lakcim,
                "szemszam" => $szemszam
            ]);
            $last_id = $conn->lastInsertId(); // idvel biztosabb dolgozni mint emaillal
            $_SESSION["username"] = $email;
            $_SESSION["admin"] = 0;
            $_SESSION["uid"] = $last_id;
            setcookie("username", $email);
            header("Location: /index.php");
        }else{
            //TODO van már ilyen account
            $errors .= "Ezzel az email címmel már regisztráltak!<br>";
        }
        
    }

}

?>


<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzabajnok Regisztráció</title>

    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
    <style>

.form-signin {
  width: 100%;
  max-width: 330px;
  padding: 15px;
  margin: auto;
}
.form-signin .checkbox {
  font-weight: 400;
}
.form-signin .form-control {
  position: relative;
  box-sizing: border-box;
  height: auto;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}

.bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

</head>

<body class="text-center">
<?php require("../include/navigation.php"); ?>

<form class="form-signin" method="POST">
  <img class="mb-4" src="../img/bank.jpg" alt="" width="72" height="72">
  <h1 class="h3 mb-3 font-weight-normal">Kérem írja be az adatait a regisztrációhoz</h1>
  <?php echo $errors; ?>
  <label for="inputEmail" class="sr-only">Email cím</label>
  <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required autofocus>
  <label for="inputNev" class="sr-only">Teljes Neve</label>
  <input type="text" id="inputNev" name="nev" class="form-control" placeholder="Teljes neve" required>
  <label for="inputSzulDate" class="sr-only">Születési Dátum</label>
  <input type="date" id="inputSzulDate" name="szuldate" class="form-control" placeholder="Születési Dátum" required>
  <label for="inputPhone" class="sr-only">Telefonszám</label>
  <input type="tel" id="inputPhone" name="phone" class="form-control" placeholder="Telefonszám" required>
  <label for="inputLakcim" class="sr-only">Lakcím</label>
  <input type="text" id="inputLakcim" name="lakcim" class="form-control" placeholder="Lakcím" required>
  <label for="inputSzemSzam" class="sr-only">Személyigazolvány szám</label>
  <input type="text" id="inputSzemSzam" name="szemszam" class="form-control" placeholder="Személyigazolvány száma" required>
  <label for="inputPassword" class="sr-only">Jelszó</label>
  <input type="password" id="inputPassword" name="jelszo" class="form-control" placeholder="Password" required>

  <div class="checkbox mb-3">
    <label>
      Ha már van fiókja <a href="/pages/login.php">itt tud bejelentkezni</a>.
    </label>
  </div>

  <button class="btn btn-lg btn-primary btn-block" type="submit">Regisztráció</button>
</form>

    <?php require("../include/footer.html"); ?>


</body>

</html>