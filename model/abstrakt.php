<?php

/**
 * abstrakcyjna klasa dla modeli
 * @author piotr
 * @version 1.0
 */
abstract class model__abstrakt {

    protected $nazwa_tabeli;
    public $instancja;

    public function __construct() {
        $this->instancja = model__baza::polacz_z_baza();
    }

    /**
     * wypisuje zawartość tablicy
     * @return ArrayObject  Zwraca tablicę z rekordami
     */
    public function pobierz($polecenie) {
        $wynik_posredni = $this->instancja->prepare($polecenie);
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
            WHERE ".$warunek.";";
        $zapytanie = $this->instancja->prepare($tresc_zapytania);
        $zapytanie->execute();
    }

    /**
     * dodaje wartosc do tabeli
     * @param string $kolumny nazwy kolumn
     * @param string $wartosci wartosci kolumn
     */
    public function dodaj_wartosci($kolumny, $wartosci) {
        $nazwy_kolumn = null;
        $wartosci_kolumn = null;
        foreach ($kolumny as $wartosc) {
            $nazwy_kolumn.=$wartosc.",";
        }
        foreach ($wartosci as $wartosc) {
            $wartosci_kolumn.="'".$wartosc."',";
        }
        $nazwy_kolumn=  rtrim($nazwy_kolumn,', ');
        $wartosci_kolumn=  rtrim($wartosci_kolumn,', ' );
        $tresc_zapytania = "
            insert into ".$this->nazwa_tabeli." ("
            .$nazwy_kolumn."
            ) values ("
            .$wartosci_kolumn."
            );
        ";

        $zapytanie = $this->instancja->prepare($tresc_zapytania);
        $zapytanie->execute();
    }

    /**
     * pobiera informacje potrzebne do zalogowania
     * @param int $uzytkownik_id
     * @return Arrayobject
     */
    public function pobierz_info_o_uzytkowniku($uzytkownik_id) {
        $zapytanie = "
            Select jest_moderatorem,uzytkownik_id,zalogowany,login
            from pk_uzytkownicy 
            where uzytkownik_id='".$uzytkownik_id."';
        ";
        
        $wynik_zapytania=$this->pobierz($zapytanie);
        
        $wynik['jest_moderatorem']=$wynik_zapytania[0]['jest_moderatorem'];
        $wynik['zalogowany']=$wynik_zapytania[0]['zalogowany'];
        $wynik['uzytkownik_id']=$wynik_zapytania[0]['uzytkownik_id'];
        $wynik['login']=$wynik_zapytania[0]['login'];
        return $wynik;
    }
    
    public function zabezpiecz($zmienna){
        $wynik=  htmlspecialchars($zmienna);
        $wynik= addslashes($wynik);
        return $wynik;
    }
}
