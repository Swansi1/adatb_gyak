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
    <title>Pizzabajnok Számla</title>

    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/mindenes.css">
    <script src="../scripts/jquery.js"></script>

</head>

<body>
<?php require("../include/navigation.php"); ?>

    <main id="container">
        <h1>Rendelés áttekintése</h1>
        <form action="szamlaatadva.php" method="GET">
            <fieldset id="rendelesAppend">
                <legend>Rendelések</legend>
                <?php 

                    require_once("../connect/connect.php");
                    $stmt = $conn->prepare("SELECT kosar.db, pizzak.id, pizzak.nev, pizzak.ar, pizzak.akcios FROM `kosar` INNER JOIN pizzak ON kosar.pizzaid = pizzak.id WHERE `kinel` = :ki");
                    $stmt->execute(["ki" => $_SESSION["uid"]]);
                    $pizzak = $stmt->fetchAll();

                    $stmt = $conn->prepare("SELECT * FROM `accounts` WHERE `id` = :ki");
                    $stmt->execute(["ki" => $_SESSION["uid"]]);
                    $userData = $stmt->fetch();

                    if($pizzak !== false){
                        foreach ($pizzak as $pizza) {
                            if($pizza["akcios"] == 0){
                                //todo nem akcios
                                print('<div class="row">
                                <input type="hidden" name="pizza_order[]" value="'. $pizza["db"] .'_'. $pizza["id"] .'"> '. $pizza["nev"] .' '. $pizza["db"] .' db '. $pizza["ar"] .' Ft/db (összesen: '. $pizza["ar"] * $pizza["db"] .' Ft)
                            </div>
                            <hr>');
                            }else{
                                print('<div class="row">
                                <input type="hidden" name="pizza_order[]" value="'. $pizza["db"] .'_'. $pizza["id"] .'"> '. $pizza["nev"] .' '. $pizza["db"] .' db '. (intval($pizza["ar"])-500) .' Ft/db (összesen: '. (intval($pizza["ar"])-500) * $pizza["db"] .' Ft)
                            </div>
                            <hr>');
                            }
                        }
                    }
                ?>
            </fieldset>
            <fieldset>
                <legend>Adatok</legend>
                <div class="row">
                    <div class="col-25">
                        <label for="vnev">Vezetéknév</label>
                    </div>
                    <div class="col-60">
                        <input type="text" id="vnev" name="vnev" placeholder="Vezetékneved..." value="<?php echo $userData["vezeteknev"]; ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="knev">Keresztnév</label>
                    </div>
                    <div class="col-60">
                        <input type="text" id="knev" name="knev" placeholder="Keresztneved.." value="<?php echo $userData["keresztnev"]; ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="email">Email cím</label>
                    </div>
                    <div class="col-60">
                        <input type="email" id="email" name="email" placeholder="Email címed.." value="<?php echo $userData["email"]; ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="telszam">Telefonszám</label>
                    </div>
                    <div class="col-60">
                        <input type="tel" id="telszam" name="telszam" placeholder="Telefonszám.." value="<?php echo $userData["telefonszam"]; ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label>Fizetési mód</label>
                    </div>
                    <div class="col-60">
                        <input type="radio" name="fizmod" checked> Bankkártyás
                        <input type="radio" name="fizmod"> Készpénz
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="megjegyz">Megjegyzés</label>
                    </div>
                    <div class="col-60">
                        <textarea id="megjegyz" name="megj" placeholder="Írj valamit.."></textarea>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Szállítási cím</legend>

                <div class="row">
                    <div class="col-25">
                        <label for="uhsz">Település, utca, házszám</label>
                    </div>
                    <div class="col-60">
                        <input type="text" id="uhsz" name="uhsz" placeholder="Utca, házszám.." value="<?php echo $userData["lakcim"]; ?>" required>
                    </div>
                </div>
            </fieldset>

            <div class="row">
                <input type="submit" value="Rendelés leadása">
                <input type="reset" value="Visszaállítás">
            </div>
        </form>

    </main>
    

    <?php require("../include/footer.html"); ?>


</body>

</html>