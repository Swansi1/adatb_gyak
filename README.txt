Tisztelt javító tanárok!

Weboldalunkat kérjük úgy teszteljék, hogy a webszerver root könyvtárában van a project vagy egy külön virtualhost lett neki létrehozva (mivel úgy is lett készítve a weboldal hogy egy pizzarendelős oldal amit feltételeznénk hogy a valóéletben is egy rendes domain alatt lenne nem pedig könyvtárakban.)

webterv_acs_zrinyi.sql tartalmazza a weboldal adatbázisát. 
MySQL kapcsolat kiépítése: 
    Ehhez be kell menni a connect mappába
    Mappán bellül található egy connect.php file
    itt át kell írni a megfelelő dolgokat.
    $servername = "localhost"; // Ebbe a sorban (4. sor) az SQL szerver ip címét tudjuk megadni
    $username = "FELHASZNÁLÓNÉV"; // Ebbe a sorban (5. sor) az SQL felhasználónevét tudjuk megadni
    $password = "JELSZO"; // Ebben a sorban (6. sor) az SQL jelszavát tudjuk megadni
    $dbname = "webterv_acs_zrinyi"; // Ebben a sorban (7. sor) a beimportált SQL adatbázis nevét

Alapból a MySQL adatbázis tartalmaz egy admin felhasználót hogy ne kelljen manuálisan átirogatni.
    Admin felhasználó: admin@admin.com
    Admin jelszó: Adminvagyok123


Oldalon megtalálható alapvető funkciók:
    Ha már be voltál jelentkezve süti alapján a bejelentkezési mezőbe beírja az email címedet
    Dinamikus pizzák betöltése php-val
    Csak akkor enged pizzát rendelni ha be vagy jelentkezve
    Kosár adatbázisba mentődik tehát más eszközön is syncelve lesz
    Üzenetküldés más felhasználóknak
    Saját profil megtekintése / publikus adatok megadása
    Saját rendelések megtekintése
    Oldalon regisztrált felhasználók keresése
    Kijelentkezés

Oldalon megtalálható admin funkciók:
    Összes leadott rendelés megtekintése
    Oldalra regisztrált felhasználók összes adatainak megtekintése
    Felhasználók blokkolása / feloldása 
    Felhasználó kijelölése adminná / felhasználóvá