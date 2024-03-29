<?php

if(!isset($_SESSION)) { 
    session_start(); 
    // session_destroy();
}

?>
<link rel="stylesheet" href="/css/bootstrap.min.css">



<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="/pages/kezdolap.php">BestBank</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="/pages/kezdolap.php">Kezdőlap <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/pages/uzenofal.php">Üzenőfal</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Funkciók
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="/pages/utalas.php">Utalás</a>
          <a class="dropdown-item" href="/pages/profil.php">Adatok módosítása</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/pages/baratAdd.php">Barát hozzáadása</a>
        </div>
      </li>
      

    <?php if(isset($_SESSION["admin"]) > 0) : ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Admin funkciók
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="/pages/admin/userEdit.php">Felhasználók kezelése</a>
          <a class="dropdown-item" href="/pages/admin/uzenofalEdit.php">Üzenet kizűtése az Üzenőfalra</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/pages/admin/stat.php">Statisztikák</a>
        </div>
      </li>
    <?php endif; ?>

      

    </ul>
    <form class="form-inline my-2 my-lg-0">
      <a class="btn <?php echo count($_SESSION) > 0 ? 'btn-outline-danger' : 'btn-outline-primary'; ?> my-2 my-sm-0" href="/pages/login.php"><?php echo count($_SESSION) > 0 ? 'Kijelentkezés' : "Bejelentkezés"; ?></a>
    </form>
  </div>
</nav>



<script src="/scripts/jquery.js"></script>
<script src="/scripts/popper.min.js"></script>
<script src="/scripts/bootstrap.min.js"></script>