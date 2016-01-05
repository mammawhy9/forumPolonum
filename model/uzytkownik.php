<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of model__uzytkownik
 *
 * @author piotr
 */
class model__uzytkownik extends model__abstrakt {

    private $baza;
    public $nazwa_tabeli="pk_uzytkownicy";
    

    public function czy_jest_moderatorem($login) {
        $zapytanie = "Select jest_moderatorem from ".$this->nazwa_tabeli." where login='".$login."';";
        
        $wynik_zapytania = $this->pobierz($zapytanie);
        return (int) $wynik_zapytania[0]['jest_moderatorem'];
    }

    public function sprawdz_ilosc($warunek) {
        $zapytanie = "Select count(*) from ".$this->nazwa_tabeli." where ".$warunek.";";

        $wynik = $this->pobierz($zapytanie);
        if (!empty($wynik)) {
            return (bool)$wynik[0]["count(*)"];
        } else {
            return 0;
        }
    }
    public function pobierz_info($kolumny,$warunek=''){
        $nazwy_kolumn=null;
        foreach($kolumny as $wartosc){
            $nazwy_kolumn.=$wartosc.' ';
        }
        $zapytanie="Select ".$nazwy_kolumn." from ".$this->nazwa_tabeli." where ".$warunek.";";
        return $this->pobierz($zapytanie);
        
    }

}
