<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of default
 *
 * @author piotr
 */
abstract class model__abstrakt {
    protected $nazwa_tabeli;
    public $polaczenie;
    public function __construct() {
        $this->polaczenie= model__baza::polacz_z_baza();
    }
    /**
     * wypisuje zawartość tablicy
     * @return ArrayObject  Zwraca tablicę z rekordami
     */
    public function pobierz($polecenie) {
        echo var_dump($polecenie);
        $wynik_posredni = $this->polaczenie->prepare($polecenie);
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
    public function aktualizuj($co, $na_co, $warunek) {
        $tresc_zapytania = "UPDATE ".$gdzie." SET ".$co."='".$na_co."'  WHERE ".$warunek.";";
        $zapytanie = $this->polaczenie->prepare($tresc_zapytania);
        $zapytanie->execute();
    }

    /*
     * dodaje wartosc do tabeli
     * @param string $gdzie nazwa tabeli
     * @param string $kolumny nazwy kolumn
     * @param string $wartosci wartosci kolumn
     */
    public function dodaj_wartosci($kolumny, $wartosci) {
        $nazwy_kolumn=null;
        $wartosci_kolumn=null;
        foreach($kolumny as $wartosc){
            $nazwy_kolumn.=$wartosc;
        }
        foreach($wartosci as $wartosc){
            $wartosci_kolumn.=$wartosc;
        }
        $tresc_zapytania = "insert into ".$this->nazwa_tabeli." (".$nazwy_kolumn.") values (".$wartosci_kolumn.");";
        echo $tresc_zapytania;
        $zapytanie = $this->polaczenie->prepare($tresc_zapytania);
        $zapytanie->execute();
    }
}
