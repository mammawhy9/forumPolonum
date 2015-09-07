<?php
class model {
    public $baza;
    /**
     * obiekt do obs³ugi bazy danych i dzialania na danych
     */
    public function __construct() {
        echo 'utworzono model<br/>';
                require 'baza.php';
                $this->baza= new baza;
    }
    /**
     * Wypisuje w±tki
     */
    public function wypisz_watki(){
       $this->baza->polecenie='Select * from pk_topics;';
       $kolumny=array("topic_id","topic_title","topic_status");
       $this->baza->wypisz_polecenie($kolumny);
       
    }
}
