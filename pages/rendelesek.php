<?php 
if(!isset($_SESSION)) { 
    session_start(); 
} 
if(!isset($_SESSION["username"])){
    header("Location: /index.php"); // ne tudja acceselni az oldalt ha már be van lépve 
} 

?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzabajnok Rendeléseim</title>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <script src="../scripts/jquery.js"></script>
    <link rel="stylesheet" href="../css/mindenes.css">

</head>

<body>
    <?php include("../include/navigation.php");   ?>

    <main>
        <h1>Saját rendeléseim</h1>
        <table class="tableCenter">
            <tr>
                <th>Mit</th>
                <th>Mennyit</th>
                <th>Mikor</th>
            </tr>
        <?php  
            include_once("../connect/connect.php");
            $stmt = $conn->prepare("SELECT pizzak.nev, rendelesek.mennyit, rendelesek.mikor FROM `rendelesek` INNER JOIN pizzak ON rendelesek.mit = pizzak.id WHERE ki = :kinel");
            $stmt->execute(["kinel" => $_SESSION["uid"]]); 
            $rendelesek = $stmt->fetchAll();

            foreach ($rendelesek as $rendeles) {
                print("<tr>");
                print("<td>". $rendeles["nev"] ."</td>");
                print("<td>". $rendeles["mennyit"] ."</td>");
                print("<td>". $rendeles["mikor"] ."</td>");
                print("</tr>");
            }
        ?>
        </table>
    </main>

    <?php include("../include/footer.html"); ?>

</body>

</html>