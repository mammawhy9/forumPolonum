<?php

/*
 * model dla tabeli pk_uzytkownicy
 *
 * @author piotr
 * @version 1.0
 */
class model__uzytkownik extends model__abstrakt {

    private $baza;
    public $nazwa_tabeli = "pk_uzytkownicy";

    /*
     * Sprawdza czy uzytkownik jest moderatorem
     * @param string $login
     * return int
     */
    public function czy_jest_moderatorem($login) {
        $zapytanie = "Select jest_moderatorem from ".$this->nazwa_tabeli." where login='".$login."';";

        $wynik_zapytania = $this->pobierz($zapytanie);
        return (int) $wynik_zapytania[0]['jest_moderatorem'];
    }

    /*
     * sprawdza ilosc wytepowania danego elementu
     * @param string $warunek
     */
    public function sprawdz_ilosc($warunek) {
        $zapytanie = "Select count(*) from ".$this->nazwa_tabeli." where ".$warunek.";";
        $wynik = $this->pobierz($zapytanie);
        if (!empty($wynik)) {
            return (bool) $wynik[0]["count(*)"];
        } else {
            return 0;
        }
    }

    /*
     * pobiera wybrane kolumny
     * @param array $kolumny
     * @param string $warunek
     * @return array
     */
    public function pobierz_info($kolumny, $warunek = '') {
        $nazwy_koslumn = null;
        foreach ($kolumny as $wartosc) {
            $nazwy_kolumn.=$wartosc.' ';
        }
        $zapytanie = "Select ".$nazwy_kolumn."
            from ".$this->nazwa_tabeli."
            where ".$warunek."
            ;
        ";
        return $this->pobierz($zapytanie);
    }

    /*
     * zalogowywuje uzytkownika
     * @param string $login
     * @param string $haslo
     */
    public function zaloguj($login, $haslo) {

        $_SESSION['zalogowany'] = 1;
        $_SESSION['dane_poprawne'] = 1;
        $this->aktualizuj('zalogowany', 1, ' login="'.$login.'"');

        //sprawdzamy czy ma uprawnienia administratora
        $wynik_moderator = $this->czy_jest_moderatorem($login);
        if ($wynik_moderator) {
            $_SESSION['jest_moderatorem'] = 1;
        } else {
            $_SESSION['jest_moderatorem'] = 0;
        }

        // pobieramy id uzytkownika
        $uzytkownik_id = $this->pobierz_info(array(
            'uzytkownik_id'),
            "login='".$login."' and haslo='".crypt($haslo, CRYPT_BLOWFISH)."'"
        );
        $_SESSION['uzytkownik_id'] = $uzytkownik_id[0]['uzytkownik_id'];
    }

    /*
     * wylogowywuje uzytkownika
     * @param int $uzykownik_id 
     */
    public function wyloguj($uzytkownik_id) {
        $this->aktualizuj('zalogowany', 0, ' uzytkownik_id='.$uzytkownik_id);
    }

    /*
     *  inicjalizuje/zeruje potrzebne do działania zmienne sesyjne
     */
    public function zeruj_zmienne_sesyjne() {
        $_SESSION['zalogowany'] = 0;
        $_SESSION['dane_poprawne'] = 0;
        $_SESSION['jest_moderatorem'] = 0;
        $_SESSION['uzytkownik_id'] = 0;
    }

}
