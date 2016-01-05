<?php

/**
 * zarządza szablonami smarty
 * @author Piotr Kowal <piotr.kowal@polskapress.pl>
 * @version 1.0
 */
class include__widok {
    /*
     * @var Smarty $szablon obiekt Smarty
     * @var String $tytul tytuł strony
     */

    private $szablon;
    private $tytul;

    /**
     * zarządza szablonami smarty
     */
    public function __construct() {
        $this->szablon = new Smarty;
    }

    /*
     * ładuje nagłówek strony
     * @param String $tytul Tytul strony
     */
    public function laduj_naglowek($tytul = 'Projekt Wstępny') {
        $this->tytul = $tytul;
        $this->szablon->assign('tytul', $this->tytul);
        $this->szablon->display("widoki/naglowek.tpl");
    }

    /*
     * ładuje konkretny szablon
     * @param array $wartosc tablica z zawartoscia tabeli
     * @param string $nazwa nazwa zmiennej w szablonie
     * @param boolean $czy_jest_moderatorem zmienna logiczna; określa przywileje moderatora 
     */
    public function laduj_widok($wartosc, $nazwa, $czy_jest_moderatorem) {
        $this->szablon->assign($nazwa, $wartosc);
        $this->szablon->assign('zalogowany', $_SESSION['zalogowany']);
        $this->szablon->assign('czy_jest_moderatorem', $czy_jest_moderatorem);
        $this->szablon->display("widoki/".$nazwa.".tpl");
    }

    /*
     * Dodaje zmienna do szablonu
     * @param string $nazwa nazwa zmiennej ktora ma byc umieszczona w szablonie
     * @param mixed $wartosc wartosc zmiennej
     */
    public function dodaj($nazwa, $wartosc) {
        $this->szablon->assign($nazwa, $wartosc);
    }

    /*
     * ładuje odpowiedni formularz
     * @param string $wartosc nazwa formularza do wyswietlenia
     */
    public function laduj_formularz($wartosc) {
        $this->szablon->assign('formularz', $wartosc);
        $this->szablon->display("widoki/formularz_logowania.tpl");
    }

    /*
     * ładuje szablon o wybranej nazwie
     * @param string $nazwa nazwa szablonu do załadowania
     */
    public function laduj_inny_szablon($nazwa) {
        $this->szablon->display("widoki/".$nazwa.".tpl");
    }

}
