<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(!isset($_SESSION)) { 
    session_start(); 
} 
if(!isset($_SESSION["username"])){
    header("Location: /index.php"); // ne tudja acceselni az oldalt ha már be van lépve 
} 

$errors = "";
$successMessage = "";
include_once("../connect/connect.php");

if(isset($_POST["kinek"]) && isset($_POST["uzenet"])){
    // üzenet küldése
    $kinek = $_POST["kinek"];
    $uzenet = $_POST["uzenet"];

    $errors = "";
    if(trim($kinek) == ""){
        $errors .= "Senkinek nem tudsz üzenetet küldeni! <br>";
    }

    if(trim($uzenet) == ""){
        $errors .= "Üres üzenet küldésének nincs értelme!";
    }

    if(trim($errors) == ""){
        $stmt = $conn->prepare("SELECT id FROM `accounts` WHERE email = :email");
        $stmt->execute(["email" => $kinek]); 
        $isExist = $stmt->fetch();
        if($isExist !== false){
            $stmt = $conn->prepare("INSERT INTO `message`(`kitol`, `kinek`, `mit`) VALUES (:kitol, :kinek, :mit)");
            $stmt->execute(["kitol" => $_SESSION["uid"], "kinek" => $isExist[0], "mit" => $uzenet]); 
            $successMessage = "Üzenet sikeresen elküldve!";
        }else{
            $errors = "Az üzenetküldés nem lehetséges mivel a felhasználó nem található!";
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
    <title>Pizzabajnok Üzenetküldés</title>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <script src="../scripts/jquery.js"></script>
    <link rel="stylesheet" href="../css/mindenes.css">

</head>

<body>
    <?php include("../include/navigation.php");  ?>

    <main id="container">
        <h1>Üzenet küldése</h1>
        <h3 style="color:green;"><?php echo $successMessage ?></h3>
        <h3 style="color:red;"><?php echo $errors ?></h3>
        <form action="uzenetkuldes.php" method="POST">
            <div class="row">
                <div class="col-25">
                    <label for="kinek">Kinek</label>
                </div>
                <div class="col-60">
                    <input type="email" id="kinek" name="kinek" placeholder="Email.." required>
                </div>
            </div>
            <div class="row">
                <div class="col-25">
                    <label for="uzenet">Mit</label>
                </div>
                <div class="col-60">
                    <input type="text" id="uzenet" name="uzenet" placeholder="Üzenet helye.." required>
                </div>
            </div>
            <input type="submit" value="Üzenet küldése">
        </form>
        <h1 style="margin-top:75px">Beérkezett üzenetek</h1>
        <?php 

            $stmt = $conn->prepare("SELECT kitol FROM `message` WHERE kinek = :uids GROUP BY kitol");
            $stmt->execute(["uids" => $_SESSION["uid"]]); 
            $messages = $stmt->fetchAll();
            foreach ($messages as $message) {
                $stmt = $conn->prepare("SELECT accounts.email, message.mit, message.mikor FROM `message` INNER JOIN accounts ON message.kitol = accounts.id  WHERE kitol = :kitol AND kinek = :uids");
                $stmt->execute(["kitol" => $message["kitol"], "uids" => $_SESSION["uid"]]); 
                $pm = $stmt->fetchAll();
                $generateP = "";
                print('<button class="collapsible">'. $pm[0]["email"] .'</button>');
                foreach ($pm as $p) {
                    $generateP .= "<p> ". $p["mit"] ."  ->  ". $p["mikor"] ."</p>";
                }
                print('<div class="content">'. $generateP .'</div>');

               
                
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