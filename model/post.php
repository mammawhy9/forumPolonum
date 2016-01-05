<?php

/**
 * model dla pk_post
 *
 * @author piotr
 * @verion 1.0
 */
class model__post extends model__abstrakt {

    /**
     * 
     * @var array $posty
     */
    public $posty;
    /**
     *
     * @var string $nazwa_tabeli
     */
    public $nazwa_tabeli = 'pk_posty';

    /**
     * dodaje post
     * @param string $post
     * @param integer $watek_id
     * @param integer $uzytkownik_id
     */
    public function dodaj_post($post, $watek_id, $uzytkownik_id) {
        $watek_id = (int)$watek_id;
        $uzytkownik_id = (int)$uzytkownik_id;
        $this->dodaj_wartosci(
            array('zawartosc' => $post, 'watek_id' => $watek_id, 'autor' => $uzytkownik_id)
        );
    }

    /**
     * zmienia status postu
     * @param string $status_postu
     * @param integer $post_id
     */
    public function zmien_status_postu($status_postu, $post_id) {
        $status_postu = $this->zabezpiecz($status_postu);
        $post_id = (int)$post_id;
        $this->aktualizuj(
            'status', $status_postu, "post_id='".$post_id."'"
        );
    }

    /**
     * filtruje posty do wyświetlenia 
     * @param integer $czy_zalogowany
     * @param integer $czy_moderator
     */
    public function filtruj_posty($czy_zalogowany, $czy_moderator) {

        $nr_watku = (int)$_GET['posty'];
        $czy_moderator = (bool)$czy_moderator;
        $czy_zalogowany = (bool)$czy_zalogowany;

        $zapytanie = "
            SELECT post_id, zawartosc, watek_id, status,
                  pk_uzytkownicy.login
            FROM ".$this->nazwa_tabeli."
            LEFT JOIN pk_uzytkownicy 
            ON pk_posty.autor = pk_uzytkownicy.uzytkownik_id
            WHERE watek_id = ".$nr_watku." ";

        //jezeli nie jest zalogowany lub nie jest moderatorem, wyswietlamyh tylko posty z danego watku ze statusem 'widoczny'
        if (!$czy_zalogowany || !$czy_moderator) {
            $zapytanie .= " AND  status!='skasowany' AND  status!='do_moderacji '"
                ." AND  status!='ukryty' ";
        }


        $this->posty = $this->pobierz($zapytanie);

        if (!empty($this->posty)) {
            foreach ($this->posty as $post) {
                $post['zawartosc'] = stripslashes($post['zawartosc']);
            }
        }
    }

    /**
     * pobiera tytul watku
     * @param integer $watek_id
     * @return string 
     */
    public function pobierz_tytul_watku($watek_id) {
        $zapytanie = "
            SELECT tytul 
            FROM pk_watki 
            LEFT JOIN pk_uzytkownicy 
            ON pk_uzytkownicy.uzytkownik_id=autor 
            WHERE watek_id=".$watek_id.";
        ";
        $wynik = $this->pobierz($zapytanie);
        $tytul_watku = $wynik[0]['tytul'];
        return $tytul_watku;
    }

}
