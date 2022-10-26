<?php 
if(!isset($_SESSION)) { 
    session_start(); 
} 
if(!isset($_SESSION["username"])){
    header("Location: /index.php");
} 

$errorMsg = "";
$successMessage = "";

?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BestBank | Üzenőfal hozzáadása</title>
</head>

<body>
    <?php 
    include("../../include/navigation.php");   
    include_once("../../connect/connect.php");


    if(isset($_POST["uzenofalUzenet"])){
        // var_dump($_POST);
        $stmt = $conn->prepare("INSERT INTO `faluzenet` (`szoveg`, `uid`) VALUES (:uzenet, :uid)");
        $stmt->execute([
            "uzenet" => $_POST["uzenofalUzenet"],
            "uid" => $_SESSION["uid"]
        ]); 
        $successMessage = "Sikeres rögzítés!";
    }

?>

    <div class="container mt-5">
        <div class="card-deck text-center">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal">Új bejegyzés hozzáadása</h4>
                </div>
                <div class="card-body">
                    <h4 class="text-success"><?php echo $successMessage; ?></h4>
                    <h4>Új bejegyzés szövege</h4>
                    <form action="" method="post">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <input type="textarea" name="uzenofalUzenet" class="form-control">
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <input type="submit" value="Hozzáadás" class="btn btn-success">
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>


    <?php include("../../include/footer.html"); ?>

</body>

</html>