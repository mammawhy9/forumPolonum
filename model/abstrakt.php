<?php

/**
 * abstrakcyjna klasa dla modeli
 * @author piotr
 * @version 1.0
 */
abstract class model__abstrakt {

    /**
     * 
     * @var string $nazwa_tabeli
     */
    protected $nazwa_tabeli;
    /**
     *
     * @var PDO $instancja_bazy 
     */
    public $instancja_bazy_bazy;

    /**
     * 
     * konstruktor klasy
     */
    public function __construct() {
        $this->polacz_z_baza();
    }

    /**
     * Kontaktuje się z bazą danych
     * Dane do logowania bierze z konfiguracji.php
     *
     */
    public function polacz_z_baza() {
        require 'model/konfiguracja.php';
        $nazwa_bazy_hosta = 'mysql:dbname='.$dbname.';host='.$hostname;
        try {
            $polaczenie_z_baza = new PDO(
                $nazwa_bazy_hosta, $username, $password,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
            );
        } catch (PDOException $e) {
            echo 'Connection failed: '.$e->getMessage();
        }
        $this->instancja_bazy = $polaczenie_z_baza;
    }

    /**
     * wypisuje zawartość tablicy
     * @return ArrayObject  Zwraca tablicę z rekordami
     */
    public function pobierz($polecenie) {
        $wynik_posredni = $this->instancja_bazy->prepare($polecenie);
        $wynik_posredni->execute();
        $wynik_koncowy = $wynik_posredni->fetchAll();
        return $wynik_koncowy;
    }

    /**
     * aktualizuje zawartosc  tabeli
     * @param string $co nazwa kolumny    
     * @param string $na_co wartość, która ma być przypisana do kolumny
     * @param string $warunek warunek aktualizacji
     */
    public function aktualizuj($co, $na_co, $warunek) {
        $tresc_zapytania = "
            UPDATE ".$this->nazwa_tabeli." 
            SET ".$co." = '".$na_co."'
            WHERE ".$warunek.";
        ";
        $zapytanie = $this->instancja_bazy->prepare($tresc_zapytania);
        $zapytanie->execute();
    }

    /**
     * dodaje wartosc do tabeli
     * @param string $kolumny nazwy kolumn
     * @param string $wartosci wartosci kolumn
     */
    public function dodaj_wartosci($kolumny) {
        $nazwy_kolumn =  implode(", ", array_keys($kolumny));
        $wartosci_kolumn =  "'".implode("', '", $kolumny)."'";
        
        
        $tresc_zapytania = "
            INSERT INTO ".$this->nazwa_tabeli." ("
            .$nazwy_kolumn."
            ) VALUES ("
            .$wartosci_kolumn."
            );
        ";

        $zapytanie = $this->instancja_bazy->prepare($tresc_zapytania);
        $zapytanie->execute();
    }


    /**
     *  zabezpiecza przed sql injection
     * @param string $zmienna
     * @return string
     */
    public function zabezpiecz($zmienna) {
        $wynik = htmlspecialchars($zmienna);
        $wynik = addslashes($wynik);
        return $wynik;
    }

}
