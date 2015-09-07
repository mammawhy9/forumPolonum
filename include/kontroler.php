<?php
session_start();
class kontroler{
    public $model;
    public $widok;
    public $uzytkownik;
    public $czy_istnieje;
    /**
    * obiekt zarzadzajacy c±la stron±
    */
    public function __construct() {
        $this->model=$this->wez_model();
        $this->widok=$this->wez_widok();
        $this->czy_istnieje=array();
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
     * @return widok Zwraca widok konkretnej podstrony
     */
    public function wez_widok(){
        require 'include/widok.php';
        $widok=new widok();
        return $widok;
    }
    /**
     * 
     * @return boolean Zwraca czy jest kto¶ zalogowany czy nie
     */
    public function czy_zalogowany(){
        if($_SESSION['zalogowany']==1){
            return true;
        }  else {
            return false;
        }
    }
    /**
     * logowanie uzytkownika
     * @param type $login Login do za³o¿onego konta
     * @param type $haslo Has³o do za³o¿onego konta
     */
    public function zaloguj($login,$haslo){
        $this->model->baza->polecenie="Select count(*) from pk_uzytkownik where login='".$login."' and haslo='".sha1($haslo)."';";
        $wynik=$this->model->baza->wypisz_polecenie();
        if($wynik[0]["count(*)"]==1){
            $_SESSION['zalogowany']=1;
            $_SESSION['dane_poprawne']=1;
            $_SESSION['jest_moderatorem']=0;
        
        }else {
            $_SESSION['zalogowany']=0;
            $_SESSION['dane_poprawne']=0;
            $_SESSION['jest_moderatorem']=0;
            $_SESSION['uzytkownik_id']=0;
        }

        $this->model->baza->polecenie="Select jest_moderatorem from pk_uzytkownik where login='".$login."' and haslo='".sha1($haslo)."';";
        $wynik_moderator=$this->model->baza->wypisz_polecenie();
        if($wynik_moderator[0]['jest_moderatorem']=='1'){
            $_SESSION['jest_moderatorem']=1;
        }else{
            $_SESSION['jest_moderatorem']=0;
        }
        
        $this->model->baza->polecenie="Select uzytkownik_id from pk_uzytkownik where login='".$login."' and haslo='".sha1($haslo)."';";
        $wynik=$this->model->baza->wypisz_polecenie();
        print_r($wynik);
      $_SESSION['uzytkownik_id']=(int)$wynik[0]['uzytkownik_id'];
  
    }
    /**
     * Zmienna =0 => wylogowanie 
     */
    public function wyloguj(){
        $this->utworz_zmienne_sesyjne();
    }
    
    /**
     * Funkcja do prostego pokaazywania b³êdów w php; mozna potem usun±æ
     */
    public function pokaz_bledy(){
        ini_set('display_errors',1);
        ini_set('display_startup_errors',1);
        error_reporting(-1);
    }
    public function czy_jest_moderatorem(){
   
        if($_SESSION['jest_moderatorem']==1){
            return true;
        }  else {
            return false;
        }    
        
            
        
    }
    public function utworz_zmienne_sesyjne(){
            $_SESSION['zalogowany']=0;
            $_SESSION['dane_poprawne']=0;
            $_SESSION['jest_moderatorem']=0;
            $_SESSION['uzytkownik_id']=0;
    }
    public function sprawdz_istnienie($argument){
     
    }
}

