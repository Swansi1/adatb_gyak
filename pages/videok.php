<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzabajnok Videók</title>

    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/videok.css">
    <script src="../scripts/jquery.js"></script>



</head>

<body>
<?php require("../include/navigation.php"); ?>

    <main class="vidinyok">
        <div class="row">
            <div class="column">
                <table>
                    <tr>
                        <th class="cim">
                            Hogyan készül?
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <video controls width="600">
                                <source src="../videozene/pizzabemutatovideo.mp4"  type="video/mp4"/>
                            </video>
                        </td>

                    </tr>
                </table>
            </div>
            <div class="column">
                <table>
                    <tr>
                        <th class="cim">
                            Promóció
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <video controls width="600">
                                <source src="../videozene/Promovideo.mp4"  type="video/mp4"/>
                            </video>
                        </td>
                    </tr>
                </table>

            </div>
        </div>

        <h1 class="cim">Havi zenei javaslatunk a pizza mellé!</h1>
        <table id="zene">
            <tr>
                <th>
                    <audio controls>
                    <source src="../videozene/MusicForEating.mp3" type="audio/mpeg" />
                   </audio>
                </th>
            </tr>
        </table>

    </main>
    <?php require("../include/footer.html"); ?>


</body>

</html>