<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of watek
 *
 * @author piotr
 */
class model__watek extends model__abstrakt {
    //funkcje dodaj watek, zmien status watku
    public $watki;
    public $nazwa_tabeli = "pk_watki";
 
    public function pobierz_watki($warunek) {
        $zapytanie = "Select * from ".$this->nazwa_tabeli." where ".$warunek;
        $this->watki = $this->pobierz($zapytanie);
    }

    public function filtruj_watki($czy_zalogowany,$czy_moderator) {
        //jezeli nie jest zalogowany lub nie jest moderatorem, wyswietlamyh tylko watki ze statusem 'widoczny'
        $warunek_watki = '';
        if (!$czy_zalogowany || !$czy_moderator) {
            $warunek_watki .= " status!='skasowany'"
                    . " and status!='do_moderacji '"
                    . " and  status!='ukryty'; ";
        } else {
            $warunek_watki = '';
        }
        
        $this->pobierz_watki($warunek_watki);
    }
    
    public function dodaj_watek($tytul_watku,$uzytkownik_id){
        $this->dodaj_wartosci(array('tytul,','autor'),array('"'.$tytul_watku.'",'.$uzytkownik_id)
                );
    }
    public function zmien_status_watku($status_watku,$watek_id){
    $this->aktualizuj('status', $status_watku, "watek_id='".$watek_id."'");         
    
    }
}
