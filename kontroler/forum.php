<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class kontroler__forum{
    private $model;
    private $widok;
    private $uzytkownik;
    private $nazwa_szablonu;
    public function __construct($co_pokazac) {
 

        $this->widok= new widok__widok();
        if($co_pokazac=='posty'){
            $this->model= new model__post();
            $this->nazwa_szablonu='posty.tpl';
        }else{
            $this->model= new model__watek();
            $this->nazwa_szablonu='watki.tpl';
            
        }
                $this->uzytkownik= $this->model->pobierz_info_o_uzytkowniku('pkowal');
                $this->ustaw_zmienne_sesji();
        echo "<br/>";
                echo var_dump($this->uzytkownik);
        $this->inicjuj();
        
    }
    public function inicjuj(){
$this->czy_dodac_watki();
//$this->czy_dodac_posty();
$this->czy_aktualizowac_watki();
//$this->czy_aktualizowac_posty();


$this->model->filtruj_watki($this->uzytkownik[0]['zalogowany'],$this->uzytkownik[0]['jest_moderatorem']);
//$this->filtruj_posty();

$this->widok->laduj_naglowek();
$this->widok->szablon->assign('czy_jest_moderatorem',$_SESSION['czy_jest_moderatorem']);
$this->widok->szablon->assign('watki',$this->model->watki);
if (isset($_GET['nr_watku'])) {
    $watek_id = (int) $_GET['nr_watku'];
    $this->widok->dodaj('watek_id', $watek_id);
}

//$this->widok->laduj_widok(
 //   $this->model->watki, 'watki',$this->uzytkownik[0]['jest_moderatorem'],
 //   $_SESSION['zalogowany']
//);
echo var_dump($_SESSION);
//$this->widok->laduj_widok(
//    $this->model->posty, 'posty', $_SESSION['czy_jest_moderatorem'],
//    $_SESSION['zalogowany']
//);
echo "<br/>".var_dump($this->model->watki);

$this->widok->szablon->display('widoki/watki.tpl');

    }
       /*
     * sprawdza czy trzeba aktualizować posty
     */
    public function czy_aktualizowac_posty() {
        $potwierdzenie = isset($_POST['post_id'])
            && $_SESSION['czy_jest_moderatorem']
            && isset($_POST['post_id']) ? 1 : 0;

        if ($potwierdzenie) {
            $status_postu = (string) $_POST['zmiana_statusu_postu'];
            $post_id = (int) $_POST['post_id'];
            $this->zmien_status_postu($status_postu,$post_id);
            $this->widok->assign('zmieniono_status_postu',true);
        }
    }
        /*
     * sprawdza czy dodać post 
     */
    public function czy_dodac_posty() {
        if (isset($_POST['zawartosc'])) {
            $post = htmlspecialchars($_POST['zawartosc']);
            $post = addslashes($post);
            if (!empty($post)) {
                $watek_id = (int) $_POST['watek_id'];
                $this->model->dodaj_post($post,$watek_id,$_SESSION['uzytkownik_id']);
                $this->widok->assign('dodano_post',true);
            } else {
                $this->widok->assign('nie_dodano_postu',false);
                $this->widok->assign('pusty_formularz',true);
            }
        }
    } 
     /*
     * sprawdza czy dodać wątek 
     */
    public function czy_dodac_watki() {
        if (isset($_POST['tytul_watku'])) {
            $tytul_watku = htmlspecialchars($_POST['tytul_watku']);
            if (!empty($tytul_watku)) {
                $this->model->dodaj_watek($tytul_watku, $_SESSION['uzytkownik_id']);
            } else {
                $this->widok->laduj_inny_szablon('niewlasciwe_dane');
            }
        }
    }

    /*
     * sprawdza czy trzeba aktualizować wątki
     */
    public function czy_aktualizowac_watki() {
        //sprawdzamy cz jest moderatorem i czy chce zmienic status watku
        $czy_aktualizowac_watki = (isset($_POST['watek_id']) &&
            $_SESSION['czy_jest_moderatorem']
            && $_POST['zmiana_statusu_watku']) ? 1 : 0;

        if ($czy_aktualizowac_watki) {
            $status_watku = (string) $_POST['zmiana_statusu_watku'];
            $watek_id = (int) $_POST['watek_id'];
            $this->model->zmien_status_watku($status_watku,$watek_id);
            
        }
    }
    public function ustaw_zmienne_sesji(){
        $_SESSION['zalogowany']=$this->uzytkownik[0]['zalogowany'];
        $_SESSION['czy_jest_moderatorem']=$this->uzytkownik[0]['jest_moderatorem'];
        $_SESSION['uzytkownik_id']=$this->uzytkownik[0]['uzytkownik_id'];
    }
    
}
