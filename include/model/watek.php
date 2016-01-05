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

    public $watki;
    public $nazwa_tabeli = "pk_watki";
 
    public function pobierz_watki($warunek) {
        $zapytanie = "Select * from .".$this->nazwa_tabeli." where ".$warunek;
        $this->watki = $this->pobierz($zapytanie);
    }

    public function filtruj_watki() {
        //jezeli nie jest zalogowany lub nie jest moderatorem, wyswietlamyh tylko watki ze statusem 'widoczny'
        $warunek_watki = '';
        if (!$this->czy_zalogowany() || !$this->czy_jest_moderatorem()) {
            $warunek_watki .= "where status!='skasowany'"
                ."and status!='do_moderacji '"
                ."and  status!='ukryty' ";
        } else {
            $warunek_watki = '';
        }
        $this->model->watki = $this->model->wez_dane($warunek_watki);
    }

    /*
     * sprawdza czy dodać wątek 
     */
    public function czy_dodac_watki() {
        if ($this->sprawdz_istnienie('tytul_watku', 'post')) {
            $tytul_watku = htmlspecialchars($_POST['tytul_watku']);
            if (!empty($tytul_watku)) {
                $this->model->baza->dodaj_wartosci(array('tytul,autor'),
                    array('"'.$tytul_watku.'",'.$_SESSION['uzytkownik_id'])
                );
            } else {
                $this->widok->laduj_inny_szablon('niewlasciwe_dane');
            }
        }
    }

    /*
     * sprawdza czy trzeba aktualizować wątki
     */
    public function czy_aktualizowac_watki() {
        //sprawdzamy cz jest moderatorem i czy chce zmienic status watku
        $czy_aktualizowac_watki = ($this->sprawdz_istnienie('watek_id', 'post') &&
            $this->czy_jest_moderatorem()
            && $this->sprawdz_istnienie('zmiana_statusu_watku', 'post')) ? 1 : 0;

        if ($czy_aktualizowac_watki) {
            $status_watku = (string) $_POST['zmiana_statusu_watku'];
            $watek_id = (int) $_POST['watek_id'];
            $this->model->baza->aktualizuj('status', $status_watku, "watek_id='".$watek_id."'");
        }
    }

}
