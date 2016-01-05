<?php

/**
 * zarządza szablonami smarty
 * @author Piotr Kowal <piotr.kowal@polskapress.pl>
 * @version 1.0
 */
class widok__widok {
    /**
     * 
     * @var Smarty $szablon obiekt Smarty
     */

    public $szablon;

    /**
     * 
     * domyslny konstruktor
     */
    public function __construct() {
        $this->szablon = new Smarty;
    }

    /**
     * ładuje odpowiedni formularz
     * @param string $wartosc nazwa formularza do wyswietlenia
     */
    public function laduj_formularz($wartosc) {
        $this->szablon->assign('formularz', $wartosc);
        $this->szablon->display("widoki/formularz_logowania.tpl");
    }

    /**
     * ładuje szablon o wybranej nazwie
     * @param string $nazwa nazwa szablonu do załadowania
     */
    public function laduj_inny_szablon($nazwa) {
        $this->szablon->display("widoki/".$nazwa.".tpl");
    }

}
