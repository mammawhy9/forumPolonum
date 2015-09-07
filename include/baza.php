<?php

/**
 * Klasa do obslugi bazy danych
 *
 * @author piotr
 */
class baza{
    public $polaczenie;
    public $polecenie;
    /**
    * Kontaktuje siê z baz± danych
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
    * @return ArrayObject  Zwraca tablicê z rekordami
    */
    public function wypisz_polecenie(){
        $wynik_koncowy=new ArrayObject;
        $wynik_posredni=$this->polaczenie->prepare($this->polecenie);
        $wynik_posredni->execute();
        $wynik_koncowy=$wynik_posredni->fetchAll();
        return $wynik_koncowy;
    }
    
    public function aktualizuj($gdzie,$co,$na_co,$warunek){
        $sql = "UPDATE pk_".$gdzie
                . " SET ".$co."='".$na_co."'  WHERE ".$warunek.";";
	$q = $this->polaczenie->prepare($sql);
	$q->execute();
    }
    
    public function dodaj_wartosci($gdzie,$kolumny,$wartosci){
        
        $sql = "insert into pk_".$gdzie
        . "(".$kolumny.") values (".$wartosci.");";
        
    echo $sql;
   $q = $this->polaczenie->prepare($sql);
   $q->execute();
    }
        
       
    


}
