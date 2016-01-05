<?php
session_start();
require('smarty/Smarty.class.php');
function loader_klas($nazwa_klasy) {
    $sciezka = str_replace('__', '/', $nazwa_klasy);
    include $sciezka.'.php';
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);


spl_autoload_register('loader_klas');

$kontroler= new kontroler__uzytkownik();
