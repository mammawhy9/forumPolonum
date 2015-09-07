<?php
/**
 * Description of View
 *
 * @author piotr
 */
class widok {
    public $watki;
    public $szablon;
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
    public function laduj_watki($wartosc){
         $this->dodaj_zmienna('tytul', 'watki');
         $this->szablon->registerObject('watki',$wartosc);         
         $this->szablon->display("widoki/watki.tpl");   
    }
    public function dodaj_zmienna($nazwa,$wartosc){
        $this->szablon->assign($$nazwa,$wartosc);
    }
}
