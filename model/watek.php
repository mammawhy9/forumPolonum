<?php

/**
 * Model dla tabeli pk_watki
 *
 * @author piotr
 * @version 1.0
 */
class model__watek extends model__abstrakt {

    /**
     *
     * @var array $watki
     */
    public $watki;
    /**
     *
     * @var string $nazwa_tabeli
     */
    public $nazwa_tabeli = "pk_watki";

    /**
     * pobiera watki
     * @param string $warunek
     */
    public function pobierz_watki($warunek) {
        $zapytanie = "
            SELECT tytul, status, autor, watek_id 
            FROM ".$this->nazwa_tabeli;
        if (!empty($warunek)) {
            $zapytanie.=" WHERE ".$warunek;
        }
        $this->watki = $this->pobierz($zapytanie);
    }

    /**
     * wybiera ktore watki maja byc pobrane
     * @param integer $czy_zalogowany
     * @param integer $czy_moderator
     */
    public function filtruj_watki($czy_zalogowany, $czy_moderator) {
        //jezeli nie jest zalogowany lub nie jest moderatorem, wyswietlamyh tylko watki ze statusem 'widoczny'
        $czy_moderator = (bool)$czy_moderator;
        $czy_zalogowany = (bool)$czy_zalogowany;
        if (!$czy_zalogowany || !$czy_moderator) {
            $warunek_watki .= " status!='skasowany'"
                ." AND status!='do_moderacji '"
                ." AND status!='ukryty'; ";
        } else {
            $warunek_watki = '';
        }

        $this->pobierz_watki($warunek_watki);
    }

    /**
     * dodaje watek
     * @param string $tytul_watku
     * @param integer $uzytkownik_id
     */
    public function dodaj_watek($tytul_watku, $uzytkownik_id) {
        $tytul_watku = $this->zabezpiecz($tytul_watku);
        $uzytkownik_id = (int)$uzytkownik_id;
        $this->dodaj_wartosci(array('tytul', 'autor'),
            array($tytul_watku, $uzytkownik_id)
        );
    }

    /**
     * zmienia status watku
     * @param string $tytul_watku
     * @param integer $uzytkownik_id
     */
    public function zmien_status_watku($status_watku, $watek_id) {
        $status_watku = $this->zabezpiecz($status_watku);
        $watek_id = (int)$watek_id;
        $this->aktualizuj('status', $status_watku, "watek_id='".$watek_id."'");
    }

}
