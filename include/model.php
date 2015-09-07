<?php
class model {
    public $baza;
    public $watki;
    public $posty;
    /**
     * obiekt do obs³ugi bazy danych i dzialania na danych
     */
    public function __construct() {
                require 'baza.php';
                $this->baza= new baza;
    }
    /**
     * Bierze w±tki z bazy
     */
    public function wez_dane($nazwa){
        echo $this->baza->polecenie;
       $this->baza->polecenie='Select * from pk_'.$nazwa.';';
       $this->watki=$this->baza->wypisz_polecenie();
    }
    
}
