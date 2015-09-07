<?php
/**
 * Description of View
 *
 * @author piotr
 */
class widok {
    public $szablon;
    public $tytul;
    /**
     * Klasa, zarz±dzaj±ca widokami
     */
    public function __construct() {
        require '/home/pkowal/www/smarty/Smarty.class.php';
        $this->szablon=new Smarty;
    }
    /**
     * S³u¿y do wypisywania w±tków
     * @param type $wartosc Tablica z w±tkami
     */
    public function laduj_naglowek($tytul='Projekt Wstêpny'){
        $this->tytul=$tytul;
        $this->szablon->assign('tytul',$this->tytul);
        $this->szablon->display("widoki/naglowek.tpl");
    }
    public function laduj_widok($wartosc,$nazwa,$czy_jest_moderatorem){                          // z³±czyæ wati i posty do 1 funkcji
        $this->szablon->assign($nazwa,$wartosc);
        $this->szablon->assign('zalogowany',$_SESSION['zalogowany']);
        $this->szablon->assign('czy_jest_moderatorem',$czy_jest_moderatorem);
        $this->szablon->display("widoki/".$nazwa.".tpl");
    }   
    public function ustaw_tryb_moderatora($argument=0){
        $this->szablon->assign('czy_jest_moderatorem',$argument);
    }
    public function laduj_formularz($wartosc){
        $this->szablon->assign('formularz',$wartosc);
        $this->szablon->display("widoki/formularz_logowania.tpl");
    }
    
    }
