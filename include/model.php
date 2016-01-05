<?php

/*
 * obiekt do obsługi bazy danych i dzialania na danych
 * @author Piotr Kowal <piotr.kowal@polskapress.pl>
 * @version 1.0
 */

class include__model {
    /*
     * @var include__baza $baza obiekt bazy
     * @var array $watki asocjacyjna tablica z wátkami
     * @var array $posty asocjacyjna tablica z postami
     */
    public $baza;
    public $watki;
    public $posty;

    /**
     * obiekt do obsługi bazy danych i dzialania na danych
     */
    public function __construct() {
        $this->baza = new include__baza;
    }

    /**
     * Bierze wątki z bazy
     * @param String $co_pobrac Nazwa tabeli bez 'pk_'
     * @param String $limit_pobrania Ograniczenie wybierania rekordów
     */
    public function wez_dane($co_pobrac, $skad_pobrac, $limit_pobrania = '') {
        $this->baza->polecenie = 'Select '.$co_pobrac.' from pk_'.$skad_pobrac.'  '.$limit_pobrania.';';
        return $this->baza->wypisz_polecenie();
    }

    /*
     * sprawdza ilość wierszy wybranej tabeli
     * @param string $gdzie nazwa tabeli do zapytania
     * @param string $warunek instrukcja warunkowa dla wybranej tabeli
     * @return string zwraca ilosc wierszy poszukiwanych danych
     */
    public function sprawdz_ilosc($gdzie, $warunek) {
        $wynik = $this->wez_dane('count(*)', $gdzie, $warunek);
        return $wynik[0]["count(*)"];
    }

    /*
     * sprawdza czy użytkownik ma prawa moderatora
     * @param string $warunek warunek do zapytania
     * @return integer zwraca 1 gdy jest a 0 gdy nie jest
     */
    public function czy_jest_moderatorem($warunek) {
        $wynik = $this->wez_dane('jest_moderatorem', 'uzytkownicy', $warunek);
        return $wynik[0][0];
    }

}
