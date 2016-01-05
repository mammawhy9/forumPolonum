<?php
session_start();
require('smarty/Smarty.class.php');
function loader_klas($nazwa_klasy) {
    $sciezka = str_replace('__', '/', $nazwa_klasy);
    include $sciezka.'.php';
}

spl_autoload_register('loader_klas');

$kontroler = new include__kontroler();

if (!$kontroler->sprawdz_istnienie('zalogowany', 'session')) {
    $kontroler->zeruj_zmienne_sesyjne();
}

$kontroler->czy_rejestrowac();
$kontroler->czy_logowac();
$kontroler->wylogowanie();
$kontroler->logowanie();

if ($kontroler->czy_zalogowany()) {
    $kontroler->widok->laduj_formularz("wylogowanie");
}

$kontroler->czy_dodac_watki();
$kontroler->czy_dodac_posty();
$kontroler->czy_aktualizowac_watki();
$kontroler->czy_aktualizowac_posty();


$kontroler->filtruj_watki();
$kontroler->filtruj_posty();

$kontroler->widok->laduj_naglowek();

if ($kontroler->sprawdz_istnienie('nr_watku', 'get')) {
    $watek_id = (int) $_GET['nr_watku'];
    $kontroler->widok->dodaj('watek_id', $watek_id);
}

$kontroler->widok->laduj_widok(
    $kontroler->model->watki, 'watki', $kontroler->czy_jest_moderatorem(),
    $kontroler->czy_zalogowany()
);
$kontroler->widok->laduj_widok(
    $kontroler->model->posty, 'posty', $kontroler->czy_jest_moderatorem(),
    $kontroler->czy_zalogowany()
);


