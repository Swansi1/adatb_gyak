<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzabajnok Akciók</title>

    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <script src="../scripts/jquery.js"></script>
    <style>
        @media print {
            nav {
                display: none;
            }
        }
    </style>
</head>

<body>
<?php require("../include/navigation.php");  ?>

    <main>
        <article id="osszesPizza">
            <?php
                require_once("../helper.php");
                getAkciosPizza();
            ?>
        </article>
    </main>
    

    <?php require("../include/footer.html"); ?>


</body>

</html>