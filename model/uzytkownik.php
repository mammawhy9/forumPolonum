<?php

/**
 * model dla tabeli pk_uzytkownicy
 *
 * @author piotr
 * @version 1.0
 */
class model__uzytkownik extends model__abstrakt {

    /**
     *
     * @var string $nazwa_tabeli 
     */
    public $nazwa_tabeli = "pk_uzytkownicy";

    /**
     * Sprawdza czy uzytkownik jest moderatorem
     * @param string $login
     * @return integer
     */
    public function czy_jest_moderatorem($login) {
        $login = $this->zabezpiecz($login);
        $zapytanie = "
            SELECT jest_moderatorem 
            FROM ".$this->nazwa_tabeli."
            WHERE login='".$login."';
        ";

        $wynik_zapytania = $this->pobierz($zapytanie);
        if (!empty($wynik_zapytania)) {
            return (int)$wynik_zapytania[0]['jest_moderatorem'];
        } else {
            return 0;
        }
    }

    /**
     * sprawdza ilosc wytepowania danego elementu
     * @param string $warunek
     * @return bool 
     */
    public function sprawdz_ilosc($warunek) {
        $warunek_zapytania = null;
        foreach ($warunek as $klucz => $wartosc) {
            $warunek_zapytania.=''.$klucz.' = "'.$wartosc.'" and ';
        }
        $warunek_zapytania = rtrim($warunek_zapytania, ' and ');
        $zapytanie = "
            SELECT count(*)
            FROM ".$this->nazwa_tabeli."
            WHERE ".$warunek_zapytania.";
        ";

        $wynik = $this->pobierz($zapytanie);
        if (!empty($wynik)) {
            return (bool)$wynik[0]["count(*)"];
        } else {
            return 0;
        }
    }

    /**
     * pobiera wybrane kolumny
     * @param array $kolumny
     * @param string $warunek
     * @return array
     */
    public function pobierz_info($kolumny, $warunek = '') {
        $nazwy_kolumn = null;
        $nazwy_kolumn = implode('', $kolumny);
        $zapytanie = "
            SELECT ".$nazwy_kolumn."
            FROM ".$this->nazwa_tabeli."
            WHERE ".$warunek.";
        ";
        return $this->pobierz($zapytanie);
    }

    /**
     * zalogowywuje uzytkownika
     * @param string $login
     * @param string $haslo
     */
    public function zaloguj($login, $haslo) {

        $login = $this->zabezpiecz($login);
        $haslo = $this->zabezpiecz($haslo);
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
        $uzytkownik_id = $this->pobierz_info(
            array('uzytkownik_id'),
            "login='".$login."' AND haslo='".crypt($haslo, CRYPT_BLOWFISH)."'"
        );
        $_SESSION['uzytkownik_id'] = $uzytkownik_id[0]['uzytkownik_id'];
    }

    /**
     * wylogowywuje uzytkownika
     * @param integer $uzykownik_id 
     */
    public function wyloguj($uzytkownik_id) {
        $this->aktualizuj('zalogowany', 0, ' uzytkownik_id='.$uzytkownik_id);
    }

    /**
     *  inicjalizuje/zeruje potrzebne do działania zmienne sesyjne
     */
    public function zeruj_zmienne_sesyjne() {
        $_SESSION['zalogowany'] = 0;
        $_SESSION['dane_poprawne'] = 0;
        $_SESSION['jest_moderatorem'] = 0;
        $_SESSION['uzytkownik_id'] = 0;
    }

}
