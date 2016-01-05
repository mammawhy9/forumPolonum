<?php

/**
 * model dla pk_post
 *
 * @author piotr
 * @verion 1.0
 */
class model__post extends model__abstrakt {

    public $posty;
    public $nazwa_tabeli = 'pk_posty';

    /**
     * dodaje post
     * @param string $post
     * @param int $watek_id
     * @param int $uzytkownik_id
     */
    public function dodaj_post($post, $watek_id, $uzytkownik_id) {
        $this->dodaj_wartosci(
            array('zawartosc', 'watek_id', 'autor'),
            array($post, $watek_id, $uzytkownik_id));
    }

    /**
     * pobiera posty
     */
    public function pobierz_posty() {
        $zapytanie = "select ".$kolumny." from".$this->nazwa_tabeli." ".$warunek.";";
    }

    /**
     * zmienia status postu
     * @param string $status_postu
     * @param int $post_id
     */
    public function zmien_status_postu($status_postu, $post_id) {
        $status_postu= $this->zabezpiecz($status_postu);
        $this->aktualizuj(
            'status', $status_postu, "post_id='".$post_id."'"
        );
    }

    /**
     * filtruje posty do wyświetlenia 
     * @param int $czy_zalogowany
     * @param int $czy_moderator
     */
    public function filtruj_posty($czy_zalogowany, $czy_moderator) {

        $nr_watku = (int) $_GET['posty'];
        $warunek_posty = "where watek_id=".$nr_watku." ";

        //jezeli nie jest zalogowany lub nie jest moderatorem, wyswietlamyh tylko posty z danego watku ze statusem 'widoczny'
        if (!$czy_zalogowany || !$czy_moderator) {
            $warunek_posty .= " and  status!='skasowany' and  status!='do_moderacji '"
                ."and  status!='ukryty' ";
        }

        $warunek_posty = "left join pk_uzytkownicy 
                on pk_posty.autor=pk_uzytkownicy.uzytkownik_id ".$warunek_posty;

        $zapytanie = "select post_id,zawartosc,
                watek_id,status,
                  pk_uzytkownicy.login  from ".$this->nazwa_tabeli." ".$warunek_posty.";";
        $this->posty = $this->pobierz($zapytanie);

        if (!empty($this->model->posty)) {
            foreach ($this->model->posty as $post) {
                $post['zawartosc'] = stripslashes($post['zawartosc']);
            }
        }
    }

    /**
     * pobiera tytul watku
     * @param int $watek_id
     */
    public function pobierz_tytul_watku($watek_id) {
        $zapytanie = "select tytul from pk_watki left join pk_uzytkownicy on pk_uzytkownicy.uzytkownik_id=autor where watek_id=".$watek_id.";";
        $wynik = $this->pobierz($zapytanie);
        return $wynik[0]['tytul'];
    }

}
