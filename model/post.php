<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of post
 *
 * @author piotr
 */
class model__post extends model__abstrakt {

    public $posty;
    public $nazwa_tabeli = 'pk_posty';

    // funkcje dodaj post, zmien status postu

    public function dodaj_post($post,$watek_id,$uzytkownik_id) {
        $this->dodaj_wartosci(
                array('zawartosc','watek_id','autor'), 
                array('"' . $post . '"', $watek_id, $uzytkownik_id ));
    }

    public function pobierz_posty() {
        $zapytanie="Select ".$kolumny." from".$this->nazwa_tabeli." ".$warunek.";";
    }

    public function zmien_status_postu($status_postu,$post_id) {
         $this->aktualizuj(
                'status', $status_postu, "post_id='".$post_id."'"
            );
    }

       /*
     * filtruje posty do wyświetlenia 
     */
    public function filtruj_posty() {
        if (isset($_GET['nr_watku'])) {
            $nr_watku = (int) $_GET['nr_watku'];
            $warunek_posty = "where watek_id=".$nr_watku." ";
            
            
            // to da sie do pobierz dodać
            //jezeli nie jest zalogowany lub nie jest moderatorem, wyswietlamyh tylko posty z danego watku ze statusem 'widoczny'
            if (!$_SESSION['zalogowany'] || !$_SESSION['czy_jest_moderatorem']) {
                $warunek_posty .= " and  status!='skasowany'"
                    ."and  status!='do_moderacji '"
                    ."and  status!='ukryty' ";
            }
            $warunek_posty="left join pk_uzytkownicy on "
                ."pk_posty.autor=pk_uzytkownicy.uzytkownik_id ".$warunek_posty;
            
            $zapytanie="select pk_posty.post_id,pk_posty.zawartosc,
                pk_posty.watek_id,pk_posty.status,
                  pk_uzytkownicy.login, pk_posty from ".$this->nazwa_tabeli." ".$warunek_posty;
            $this->posty = $this->model->pobierz($zapytanie);
        }
        if(!empty($this->model->posty)){
        foreach ($this->model->posty as $post) {
            $post['zawartosc'] = stripslashes($post['zawartosc']);
        }
        }
    }
    

}
