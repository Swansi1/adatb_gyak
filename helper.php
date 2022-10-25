<?php 
require_once("connect/connect.php");


function getAllPizza(){
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM `pizzak`");
    $stmt->execute(); 
    $pizzak = $stmt->fetchAll();
    
    foreach ($pizzak as $pizza) {
        if($pizza["akcios"] == 0){
            //normál áras pizza
            loadNormalArasPizza($pizza);
        }else{
            //akciós
            loadAkciosPizza($pizza);
        }
    }
}

function getAkciosPizza(){
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM `pizzak` WHERE `akcios` = 1");
    $stmt->execute(); 
    $pizzak = $stmt->fetchAll();
    
    foreach ($pizzak as $pizza) {
        loadAkciosPizza($pizza);
    }
}



function loadNormalArasPizza($pizza){
    if(isset($_SESSION["uid"])){
        print('<div class="pizza">
        <img src="' . $pizza["kepurl"] . '" alt="' . $pizza["nev"] . '" style="height: 230px">
        <h1 class="pizzacim">' . $pizza["nev"] . '</h1>
        <p class="price">' . $pizza["ar"] . ' Ft</p>
        <p class="hozzavalokcim">Hozzávalók listája:</p>
        <p class="hozzavalok">' . $pizza["hozzavalok"] . '</p>
        <p><button onclick="pizza.addKosar(' . $pizza["id"] . ')">Megrendelés</button></p>
        </div>');
    }else{
        print('<div class="pizza">
        <img src="' . $pizza["kepurl"] . '" alt="' . $pizza["nev"] . '" style="height: 230px">
        <h1 class="pizzacim">' . $pizza["nev"] . '</h1>
        <p class="price">' . $pizza["ar"] . ' Ft</p>
        <p class="hozzavalokcim">Hozzávalók listája:</p>
        <p class="hozzavalok">' . $pizza["hozzavalok"] . '</p>
        <p><button>Megrendelés (Előbb jelentkezz be!)</button></p>
        </div>');
    }
    
}

function loadAkciosPizza($pizza){
    if(isset($_SESSION["uid"])){
        print('<div class="pizza">
        <img src="' . $pizza["kepurl"] . '" alt="' . $pizza["nev"] . '" style="height: 230px">
        <h1 class="pizzacim">' . $pizza["nev"] . '</h1>
        <p class="priceAkcios">'. $pizza["ar"] .' Ft</p>
        <p class="price">'. (intval($pizza["ar"]) - 500) .' Ft</p>
        <p class="hozzavalokcim">Hozzávalók listája:</p>
        <p class="hozzavalok">' . $pizza["hozzavalok"] . '</p>
        <p><button onclick="pizza.addKosar(' . $pizza["id"] . ')">Megrendelés</button></p>
        </div>');
    }else{
        print('<div class="pizza">
    <img src="' . $pizza["kepurl"] . '" alt="' . $pizza["nev"] . '" style="height: 230px">
    <h1 class="pizzacim">' . $pizza["nev"] . '</h1>
    <p class="priceAkcios">'. $pizza["ar"] .' Ft</p>
    <p class="price">'. (intval($pizza["ar"]) - 500) .' Ft</p>
    <p class="hozzavalokcim">Hozzávalók listája:</p>
    <p class="hozzavalok">' . $pizza["hozzavalok"] . '</p>
    <p><button>Megrendelés (Előbb jelentkezz be!)</button></p>
    </div>');
    }
    
}


?>