<?php
class kontroler{
    public $model;
    public $widok;
    /**
    * obiekt zarzadzajacy c±la stron±
    */
    public function __construct() {
        echo "utworzono kontroler<br/>";
        $this->model=$this->wez_model();
        $this->widok=$this->wez_widok();
    }
    /**
     * 
     * @return \model Zwraca model do obs³ugi konkretnj podstrony
     */
    public function wez_model(){
        require 'include/model.php';
        $model= new model();
        return $model;
    }
    /**
     * 
     * @return \widok Zwraca widok konkretnej podstrony
     */
    public function wez_widok(){
        require 'include/widok.php';
        $widok=new widok();
        return $widok;
    }
}

