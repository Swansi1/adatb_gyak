<?php 

if(!isset($_SESSION)) { 
    session_start(); 
} 
if(!isset($_SESSION["username"])){
   header("Location: index.php");
} 

    // [pizza_order] => Array
    //     (
    //         [0] => 1_3
    //         [1] => 2_1
    //     )

    // [vnev] => asd
    // [knev] => asd
    // [email] => asd@asd.com
    // [telszam] => 06705586541
    // [fizmod] => on
    // [megj] => 
    // [uhsz] => asd


if(isset($_GET["pizza_order"])){
    $errors = "";
    $pizza_order = $_GET["pizza_order"];
    $vnev = $_GET["vnev"];
    $knev = $_GET["knev"];
    $email = $_GET["email"];
    $telszam = $_GET["telszam"];
    $megj = $_GET["megj"];
    $uhsz = $_GET["uhsz"];

    if(count($pizza_order) == 0){
        $errors .= "Nem rendeltél semmit!<br>";
    }

    if(trim($vnev) == "" || trim($knev) == ""){
        $errors .= "Hibás név!<br>";
    }

    if(!strpos($email, '@') || !strpos($email, '.')){
        $errors .= "Helytelen email cím!<br>";
    }

    if(trim($uhsz) == ""){
        $errors .= "Nincs megadva lakcím!<br>";
    }

    if($errors == ""){
        require_once("../connect/connect.php");
        // nincs hiba
        foreach ($pizza_order as $pizza) {
            $cpizza = explode("_",$pizza);
            $stmt = $conn->prepare("INSERT INTO `rendelesek`(`ki`, `mit`, `mennyit`) VALUES (:ki, :mit, :mennyit)");
            $stmt->execute([
                "ki" => $_SESSION["uid"],
                "mit" => $cpizza[1],
                "mennyit" => $cpizza[0]
            ]);
            $stmt = $conn->prepare("DELETE FROM `kosar` WHERE kinel = :ki AND pizzaid = :mit");
            $stmt->execute([
                "ki" => $_SESSION["uid"],
                "mit" => intval($cpizza[1])
            ]);
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
    <title>Pizzabajnok Rendelés Leadva</title>

    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href='https://fonts.googleapis.com/css?family=Finger%20Paint' rel='stylesheet'>
    <link rel="stylesheet" href="../css/main.css">
    <script src="../scripts/jquery.js"></script>

    <style>
        table {
            margin-left: auto;
            margin-right: auto;
            margin-top: 13%;
            margin-bottom: 4%;
            line-height: 150px;
            background-color: #333;
            color: white;
            font-family: 'Finger Paint';
            font-size: 40px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.5);
        }
       
        td > a{color:chartreuse}
    </style>

</head>

<body>
<?php require("../include/navigation.php"); ?>

    <main>
        <table>
            <tr>
                <td>A rendelésed <a>sikeresen</a> leadtad, <a>5</a> óra múlva ki is lesz szállítva!</td>
            </tr>
        </table>
    </main>



    <?php require("../include/footer.html"); ?>


</body>

</html>