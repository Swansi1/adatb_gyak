<?php 
if(!isset($_SESSION)) { 
    session_start(); 
} 
if(!isset($_SESSION["username"])){
    header("Location: /index.php"); // ne tudja acceselni az oldalt ha már be van lépve 
} 

$errors = "";
$successMessage = "";

?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzabajnok Felhasználó kereső</title>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <script src="../scripts/jquery.js"></script>
    <link rel="stylesheet" href="../css/mindenes.css">
    <link rel="stylesheet" href="../css/osszecsukhato.css">

</head>

<body>
    <?php include("../include/navigation.php");  ?>

    <main id="container">
        <h1>Oldalra regisztrált felhasználók</h1>
        <?php 
            include_once("../connect/connect.php");
            $helyettesitoTomb = [
                "vezeteknev" => "Vezetéknév",
                "keresztnev" => "Keresztnév",
                "email" => "Email cím",
                "telefonszam" => "Telefonszám",
                "kedvenc_pizza" => "Kedvenc pizzái",
                "lakcim" => "Lakcím",
                "birth" => "Születési dátum"
            ];
            $stmt = $conn->prepare("SELECT * FROM `accounts`  WHERE publicData != '[]'");
            $stmt->execute(); 
            $users = $stmt->fetchAll();
            // $teszt = unserialize($users[0]["publicData"]);
            // var_dump($teszt);
            // var_dump($users[0][$teszt[0]]);
            foreach ($users as $user) {
                $publicData = unserialize($user["publicData"]);
                $kiirandoNev = "Nincs megadva";
                $generateP = "";
                foreach ($publicData as $data) {
                    if($kiirandoNev == "Nincs megadva"){
                        if($data == "email"){
                            $kiirandoNev = $user[$data];
                        }
                        if($data == "vezeteknev"){
                            $kiirandoNev = $user[$data];
                        }
                        if($data == "keresztnev"){
                            $kiirandoNev = $user[$data];
                        }
                    }
                    $generateP .= "<p>". $helyettesitoTomb[$data]  .": ". $user[$data] ." </p>";
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