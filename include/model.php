<?php
class model {
    public $baza;
    public $watki;
    public $posty;
    /**
     * obiekt do obs�ugi bazy danych i dzialania na danych
     */
    public function __construct() {
                require 'baza.php';
                $this->baza= new baza;
    }
    /**
     * Bierze w�tki z bazy
     */
    public function wez_watki(){
       $this->baza->polecenie='Select * from pk_topics;';
       $this->watki=$this->baza->wypisz_polecenie();
    }
    
}