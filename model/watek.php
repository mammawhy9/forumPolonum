<?php
/*
 * Model dla tabeli pk_watki
 *
 * @author piotr
 * @version 1.0
 */
class model__watek extends model__abstrakt {

    //funkcje dodaj watek, zmien status watku
    public $watki;
    public $nazwa_tabeli = "pk_watki";

    /*
     * pobiera watki
     * @param string $warunek
     */
    public function pobierz_watki($warunek) {
        $zapytanie = "
            Select * 
            from ".$this->nazwa_tabeli;
        if (!empty($warunek)) {
            $zapytanie.=" where ".$warunek;
        }
        $this->watki = $this->pobierz($zapytanie);
    }

    /*
     * wybiera ktore watki maja byc pobrane
     * @param int $czy_zalogowany
     * @param int $czy_moderator
     */
    public function filtruj_watki($czy_zalogowany, $czy_moderator) {
        //jezeli nie jest zalogowany lub nie jest moderatorem, wyswietlamyh tylko watki ze statusem 'widoczny'
        $warunek_watki = '';
        if (!$czy_zalogowany || !$czy_moderator) {
            $warunek_watki .= " status!='skasowany'"
                ." and status!='do_moderacji '"
                ." and  status!='ukryty'; ";
        } else {
            $warunek_watki = '';
        }

        $this->pobierz_watki($warunek_watki);
        
    }

    /*
     * dodaje watek
     * @param string $tytul_watku
     * @param int $uzytkownik_id
     */
    public function dodaj_watek($tytul_watku, $uzytkownik_id) {
        $tytul_watku = addslashes($tytul_watku);
        $this->dodaj_wartosci(array('tytul', 'autor'),
            array($tytul_watku,$uzytkownik_id)
        );
    }

    /*
     * zmienia status watku
     * @param string $tytul_watku
     * @param int $uzytkownik_id
     */
    public function zmien_status_watku($status_watku, $watek_id) {
        $this->aktualizuj('status', $status_watku, "watek_id='".$watek_id."'");
    }

}
