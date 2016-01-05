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
    public $nazwa_tabeli='pk_posty';
    /*
     * sprawdza czy trzeba aktualizować posty
     */
    public function czy_aktualizowac_posty() {
        $potwierdzenie = $this->sprawdz_istnienie('post_id', 'post')
            && $this->czy_jest_moderatorem()
            && $this->sprawdz_istnienie('post_id', 'post') ? 1 : 0;

        if ($potwierdzenie) {
            $status_postu = (string) $_POST['zmiana_statusu_postu'];
            $post_id = (int) $_POST['post_id'];
            $this->aktualizuj(
                'status', $status_postu, "post_id='".$post_id."'"
            );
        }
    }
        /*
     * sprawdza czy dodać post 
     */
    public function czy_dodac_posty() {
        if ($this->sprawdz_istnienie('zawartosc', 'post')) {
            $post = htmlspecialchars($_POST['zawartosc']);
            $post = addslashes($post);
            if (!empty($post)) {
                $watek_id = (int) $_POST['watek_id'];
                $this->model->baza->dodaj_wartosci(
                    array('zawartosc,watek_id,autor'),
                    array('"'.$post.'"',$watek_id,$_SESSION['uzytkownik_id']));
            } else {
                $this->widok->laduj_inny_szablon('niewlasciwe_dane');
            }
        }
    }
        /*
     * filtruje posty do wyświetlenia 
     */
    public function filtruj_posty() {
        if ($this->sprawdz_istnienie('nr_watku', 'get')) {
            $nr_watku = (int) $_GET['nr_watku'];
            $warunek_posty = "where watek_id=".$nr_watku." ";

            //jezeli nie jest zalogowany lub nie jest moderatorem, wyswietlamyh tylko posty z danego watku ze statusem 'widoczny'
            if (!$this->czy_zalogowany() || !$this->czy_jest_moderatorem()) {
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
