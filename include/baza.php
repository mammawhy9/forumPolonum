<?php

/**
 * Description of baza
 *
 * @author piotr
 */
class baza{
    public $polaczenie;
    public $polecenie;
            /**
             * Kontaktuje siê z baz± danych i zwraca ¿±dane wyniki
             * Dane do logowania bierze z konfiguracji.php
             */
            public function __construct() {
     require 'include/konfiguracja.php';
        $nazwa_bazy_hosta = 'mysql:dbname='.$dbname.';host='.$hostname;
        try {
                $this->polaczenie = new PDO($nazwa_bazy_hosta, $username, $password);
            }catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
        }
        /**
         * Wypisuje zawarto¶æ tablicy
         * @todo ogarn±æ nazwy i zwracanie warto¶ci
         * @param type $kolumna Tablica zawieraj±ca nazwy kolumn, które chcemy napisaæ
         */
        public function wypisz_polecenie($kolumna){
            $wynik=new ArrayObject;
            $q=$this->polaczenie->prepare($this->polecenie);
            $q->execute();
            $wynik=$q->fetchAll();
            print_r($wynik);
            echo var_dump($q);
            echo "<br/>";
        
       
        }
       
    


}
