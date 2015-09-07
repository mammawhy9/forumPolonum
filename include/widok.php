<?php
/**
 * Description of View
 *
 * @author piotr
 */
class widok {
    public $watki;
    public $szablon;
    /**
     * Klasa, zarz±dzaj±ca widokami
     */
    public function __construct() {
        require '/home/pkowal/www/smarty/Smarty.class.php';
        $this->szablon=new Smarty;
    }
    
    public function wypisz_dane($dane,$kolumny){
        foreach ($dane as $klucz=>$wartosc ){  
            for($i=0;$i<count($kolumny);$i++){
                echo $wartosc[$klucz[i]];    
            }
        
        }
    }
    /**
     * S³u¿y do wypisywania w±tków
     * @param type $wartosc Tablica z w±tkami
     */
    public function laduj_watki($wartosc){
         
         $this->dodaj_zmienna('tytul', 'watki');
         $this->szablon->registerObject('watki',$wartosc);         
         $this->szablon->display("widoki/watki.tpl");   
    }
    public function dodaj_zmienna($nazwa,$wartosc){
        $this->szablon->assign($$nazwa,$wartosc);
    }
}
