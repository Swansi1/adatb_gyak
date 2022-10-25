<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!isset($_SESSION)) { 
    session_start(); 
} 
if(isset($_SESSION["username"])){
    header("Location: /index.php"); // ne tudja acceselni az oldalt ha már be van lépve 
} 

$errors = "";
if($_SERVER['REQUEST_METHOD'] == 'POST'){


    $email = $_POST["email"];
    $password = $_POST["password"];


    if(trim($password) == ""){
        $errors .= "Nem lehet üres a jelszó! <br>";
    }
    if(trim($email) == ""){
        $errors .= "Nem lehet üres az email! <br>";
    }

    if(!strpos($email, '@') || !strpos($email, '.')){
        $errors .= "Helytelen email cím!<br>";
    }

    if($errors == ""){// NINCS HIBA
        // megnézzük hogy van-e ilyen felhasználó regisztrálva
        require_once("../connect/connect.php"); // mysql meghívás
        $stmt = $conn->prepare("SELECT uid,email,jelszo,rang FROM `user` WHERE `email` LIKE :email");
        $stmt->execute(["email" => $email]); 
        $isExists = $stmt->fetch();
        if($isExists && password_verify($password, $isExists["jelszo"])){
            $_SESSION["username"] = $email;
            $_SESSION["admin"] = $isExists["rang"];
            $_SESSION["uid"] = $isExists["uid"];
            setcookie("username", $email);
            header("Location: /index.php");
        }else{
            //TODO nincs ilyen felhasználó
            // print_r($errors);
            $errors = "Hibás felhasználónév vagy jelszó!";
            // header("Location: /pages/login.php?errmsg=hibás felhasználónév vagy jelszó!");
        }
        
    }else{
        header("Location: /pages/login.php?errmsg=". urlencode($errors));
    }

}

?>


<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzabajnok Bejelentkezés</title>

    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href='https://fonts.googleapis.com/css?family=Finger%20Paint' rel='stylesheet'>

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
  <h1 class="h3 mb-3 font-weight-normal">Kérem jelentkezzen be</h1>
  <label for="inputEmail" class="sr-only">Email cím</label>
  <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required autofocus>
  <label for="inputPassword" class="sr-only">jelszó</label>
  <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>

  <div class="checkbox mb-3">
    <label>
      Ha még nincs fiókja <a href="/pages/reg.php">itt tud regisztrálni</a>.
    </label>
  </div>

  <button class="btn btn-lg btn-primary btn-block" type="submit">Bejelentkezés</button>
</form>



    <?php require("../include/footer.html"); ?>


</body>

</html>