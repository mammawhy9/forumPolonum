<?php

/**
 * Klasa do obslugi bazy danych
 *
 * @author piotr
 * @version 1.0
 */
class include__baza {
    /*
     * @var PDO $polaczenie obiekt do laczenia sie z baza danych
     * @var String $polecenie zapytanie do bazy danych
     */

    public $polaczenie;
    public $polecenie;

    /**
     * Kontaktuje się z bazą danych
     * Dane do logowania bierze z konfiguracji.php
     *
     */
    public function __construct() {
        require 'include/konfiguracja.php';
        $nazwa_bazy_hosta = 'mysql:dbname='.$dbname.';host='.$hostname;
        try {
            $this->polaczenie = new PDO(
                $nazwa_bazy_hosta, $username, $password,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
            );
        } catch (PDOException $e) {
            echo 'Connection failed: '.$e->getMessage();
        }
    }

    /**
     * wypisuje zawartość tablicy
     * @return ArrayObject  Zwraca tablicę z rekordami
     */
    public function wypisz_polecenie() {
        $wynik_posredni = $this->polaczenie->prepare($this->polecenie);
        $wynik_posredni->execute();
        $wynik_koncowy = $wynik_posredni->fetchAll();
        return $wynik_koncowy;
    }

    /*
     * aktualizuje zawartosc wybranej tabeli
     * @param string $gdzie nazwa tabeli
     * @param string $co nazwa kolumny    
     * @param string $na_co wartość, która ma być przypisana do kolumny
     * @param string $warunek warunek aktualizacji
     */
    public function aktualizuj($gdzie, $co, $na_co, $warunek) {
        $tresc_zapytania = "UPDATE pk_".$gdzie." SET ".$co."='".$na_co."'  WHERE ".$warunek.";";
        $zapytanie = $this->polaczenie->prepare($tresc_zapytania);
        $zapytanie->execute();
    }

    /*
     * dodaje wartosc do tabeli
     * @param string $gdzie nazwa tabeli
     * @param string $kolumny nazwy kolumn
     * @param string $wartosci wartosci kolumn
     */
    public function dodaj_wartosci($gdzie, $kolumny, $wartosci) {
        $tresc_zapytania = "insert into pk_".$gdzie."(".$kolumny.") values (".$wartosci.");";
        $zapytanie = $this->polaczenie->prepare($tresc_zapytania);
        $zapytanie->execute();
    }

}
