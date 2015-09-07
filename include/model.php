<?php
class model {
    public $baza;
    public $watki;
    public $posty;
    /**
     * obiekt do obs³ugi bazy danych i dzialania na danych
     */
    public function __construct() {
                require 'baza.php';
                $this->baza= new baza;
    }
    /**
     * Bierze w±tki z bazy
     * @param String $co_pobrac Nazwa tabeli bez 'pk_'
     * @param Srting $limit_pobrania Ograniczenie wybierania rekordów
     */
    public function wez_dane($co_pobrac,$skad_pobrac,$limit_pobrania=''){
       $this->baza->polecenie='Select '.$co_pobrac.' from pk_'.$skad_pobrac.' '.$limit_pobrania.';';
       return $this->baza->wypisz_polecenie();
    }
    
    // przerob watki i przerob posty na 1 funkcje
    public function przerob_watki(){
        $tab=new ArrayObject();

        for($i=0;$i<count($this->watki);$i++){
            $tab[$i]['topic_id']=$this->watki[$i]['topic_id'];
            $tab[$i]['topic_title']=$this->watki[$i]['topic_title'];
            $tab[$i]['topic_status']=$this->watki[$i]['topic_status'];
            $tab[$i]['autor']=$this->watki[$i]['autor'];
        }
        $this->watki=$tab;
    }
    public function przerob_posty(){
      
        if(count($this->posty)){
            $tab=new ArrayObject();
            $autorzy=$this->znajdz_autora();
            for($i=0;$i<count($this->posty);$i++){
            $tab[$i]['post_id']=$this->posty[$i]['post_id'];
            $tab[$i]['zawartosc']=$this->posty[$i]['zawartosc'];
            $tab[$i]['posts_status']=$this->posty[$i]['posts_status'];
            $tab[$i]['topic_id']=$this->posty[$i]['topic_id'];
            $tab[$i]['autor']=$autorzy[$this->posty[$i]['autor']]['login'];
        }

        
        $this->posty=$tab;
        }
        
    }
    /**
     * 
     * @return Array tablica z loginami autorów
     */
    public function znajdz_autora(){
        $autorzy=$this->wez_dane('autor', 'post');
        foreach($autorzy as $wynik){
            $autorzy=$this->wez_dane('login', 'uzytkownik',$wynik);
        }
       return $autorzy; 
    }
    public function zmien_status_postu($gdzie_zmienic,$co_zmienic,$na_co_zmienic){
        $this->baza->polecenie='Update '.$gdzie_zmienic.' set '.$co_zmienic.'="'.$na_co_zmienic.'";';
        $this->baza->wypisz_polecenie();
        
    }
}
