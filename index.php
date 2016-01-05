<?php

session_start();
require('smarty/Smarty.class.php');
function loader_klas($nazwa_klasy) {
    $sciezka = str_replace('__', '/', $nazwa_klasy);
    include $sciezka.'.php';
}

spl_autoload_register('loader_klas');
$czy_logowanie=isset($_GET['zaloguj']) || isset($_GET['zarejestruj']);
$czy_watki=isset($_GET['watki']);
$czy_posty=isset($_GET['posty']);
if ($czy_logowanie) {
    $kontroler = new kontroler__uzytkownik();
} elseif ($czy_watki) {
    $kontroler = new kontroler__forum('watki');
} elseif ($czy_posty) {
    $kontroler = new kontroler__forum('posty');
} else {
    $kontroler = new kontroler__forum('watki');
}

