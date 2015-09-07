<?php
session_start();
class kontroler{
    public $model;
    public $widok;
    public $sesja;
    public $uzytkownik;
    /**
    * obiekt zarzadzajacy c±la stron±
    */
    public function __construct() {
        $this->model=$this->wez_model();
        $this->widok=$this->wez_widok();
    }
    /**
     * 
     * @return \model Zwraca model do obs³ugi konkretnj podstrony
     */
    public function wez_model(){
        require 'include/model.php';
        $model= new model();
        return $model;
    }
    /**
     * 
     * @return \widok Zwraca widok konkretnej podstrony
     */
    public function wez_widok(){
        require 'include/widok.php';
        $widok=new widok();
        return $widok;
    }
    
    public function czy_zalogowany(){
        if($_SESSION['zalogowany']==1){
            return true;
        }  else {
            return false;
        }
    }
    
    public function zaloguj($login,$haslo){
        $this->model->baza->polecenie="Select count(*) from pk_uzytkownik where login='".$login."' and haslo='".sha1($haslo)."';";
        $wynik=$this->model->baza->wypisz_polecenie();
        if($wynik[0]["count(*)"]==1){
            $_SESSION['zalogowany']=1;
        }else $_SESSION['zalogowany']=0;
        
    }
    
    public function wyloguj(){
        $_SESSION['zalogowany']=0;
    }
    public function pokaz_bledy(){
        ini_set('display_errors',1);
        ini_set('display_startup_errors',1);
        error_reporting(-1);
    }
}

