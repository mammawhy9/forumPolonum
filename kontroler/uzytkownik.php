<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of uzytkownik
 *
 * @author piotr
 */
class kontroler__uzytkownik {

    public $widok;
    public $model;

    public function __construct() {
        $this->widok = new widok__widok();
        $this->model = new model__uzytkownik();
         echo preg_match("/[\W]/", 'ggdgdg');

        if (!isset($_SESSION['zalogowany'])) {
                     $this->zeruj_zmienne_sesyjne();
        }
        $this->inicjuj();
       //   $this->widok->inicjuj();
      //    $this->widok->laduj_szablon('uzytkownik.tpl');
    }

    public function inicjuj() {
        $this->czy_rejestrowac();
        echo var_dump($_POST);
        echo var_dump($_GET);

          
        $this->czy_logowac();
        $this->wylogowanie();
        $this->logowanie();

        if ($_SESSION['zalogowany']) {
             $this->widok->laduj_formularz("wylogowanie");
        }
        echo var_dump($_SESSION);
    }

    /*
     * sprawdza czy konieczne jest zaladowanie formularza
     */
    public function czy_logowac() {
        $warunek_logowanie = (!$_SESSION['zalogowany']) && (!$_SESSION['dane_poprawne'])
            && ($this->sprawdz_istnienie('login', 'post')) && ($this->sprawdz_istnienie('zarejestruj',
                'post')) ? 1 : 0;

        if ($warunek_logowanie) {
            $this->widok->laduj_formularz("logowanie");
        }
    }

    /*
     * sprawdza czy rejestrowac; jezeli tak to rejestruje
     */
    public function czy_rejestrowac() {
        $sprawdzenie = isset($_GET['zarejestruj'])&& ($_GET['zarejestruj'])
            && isset($_POST['login_rejestracja']) && isset($_POST['login_rejestracja']);
        echo $sprawdzenie;    
        if ($sprawdzenie) {
            $login=$_POST['login_rejestracja'];
            
            if (!preg_match("/[\W]/", $login)) {
                $imie = htmlspecialchars($_POST['imie']);
                $nazwisko = htmlspecialchars($_POST['nazwisko']);
                $login = htmlspecialchars($_POST['login_rejestracja']);
                $haslo = htmlspecialchars($_POST['haslo_rejestracja']);
                $this->rejestruj($imie, $nazwisko, $login, $haslo);
            } else {
                 $this->widok->laduj_inny_szablon('niewlasciwe_dane');
            }
        } elseif (isset($_GET['zarejestruj'])) {
             $this->widok->laduj_inny_szablon('niewlasciwe_dane');
        }
    }

    /*
     * wylogowanie użytkownika przez wyzerowanie zmiennych sesyjnych
     */
    public function wylogowanie($login) {
        echo isset($_POST['wyloguj']);
        if (isset($_POST['wyloguj'])) {
            $czy_wylogowac = (bool) $_POST['wyloguj'];
            if ($czy_wylogowac) {
                $this->zeruj_zmienne_sesyjne();
                $this->model->wyloguj($login);
            }
        }
    }

    /*
     * rejestrowanie uzytkownika
     * @param string $imie imię użytkownika
     * @param string $nazwisko nazwisko użytkownika
     * @param string $login login użytkownika
     * @param string $haslo haslo użytkownika
     */
    public function rejestruj($imie, $nazwisko, $login, $haslo) {
        $haslo = \sha1($haslo);
        $warunek_rejestracji = $this->model->sprawdz_ilosc("s login='".$login."'") 
            && !empty($login) && !empty($haslo);
        
        if (!$warunek_rejestracji) {
            $this->model->dodaj_wartosci(array('imie,','nazwisko,','login,','haslo,','jest_moderatorem'),
                array("'".$imie."',","'".$nazwisko."',","'".$login."',","'".$haslo."',",0));
        } else {
            $this->widok->laduj_inny_szablon('niewlasciwe_dane');
            $this->zeruj_zmienne_sesyjne();
        }
    }

    /*
     * obsluga logowania
     */
    public function logowanie() {
        if ($this->sprawdz_istnienie('zalogowany', 'session')) {
            // jeżeli niezalogowany to sprawdzamy czy przez post dostalismy jakis login i haslo 
            if (!$_SESSION['zalogowany']) {
                $warunek = $this->sprawdz_istnienie('login', 'post') && $this->sprawdz_istnienie('haslo',
                        'post');
                if ($warunek) {
                    $login = htmlspecialchars($_POST['login']);
                    $haslo = htmlspecialchars($_POST['haslo']);

                    if (!empty($login) && !empty($haslo)) {
                        $this->zaloguj($login, $haslo);
                    } else {
                        $this->widok->laduj_inny_szablon('niewlasciwe_dane');
                       $this->widok->laduj_formularz("logowanie");
                    }
                } else {
                    $this->widok->laduj_formularz("logowanie");
                }
            }
        }
    }

    /**
     * logowanie uzytkownika i nadawanie mu praw oraz pobieranie id uzytkownika
     * @param String $login Login do konta
     * @param String $haslo Hasło do konta
     */
    public function zaloguj($login, $haslo) {
        // sprawdzamy cz istnieje taki uzytkownik
        $czy_istnieje_uzytkownik = $this->model->sprawdz_ilosc(
            " login='".$login."'"." and haslo='".sha1($haslo)."'"
        );

        if ($czy_istnieje_uzytkownik) {
            $_SESSION['zalogowany'] = 1;
            $_SESSION['dane_poprawne'] = 1;
            $this->model->zaloguj($login);    
            //sprawdzamy czy ma uprawnienia administratora
            $wynik_moderator = $this->model->czy_jest_moderatorem($login);
            if ($wynik_moderator) {
                $_SESSION['jest_moderatorem'] = 1;
            } else {
                $_SESSION['jest_moderatorem'] = 0;
            }

            // pobieramy id uzytkownika
            $uzytkownik_id = $this->model->pobierz_info(array(
                'uzytkownik_id'),
                "login='".$login."' and haslo='".sha1($haslo)."'"
            );
            $_SESSION['uzytkownik_id'] = $uzytkownik_id[0]['uzytkownik_id'];
        } else {
            $this->widok->laduj_inny_szablon('niewlasciwe_dane');
            $this->widok->laduj_formularz("logowanie");
            $this->zeruj_zmienne_sesyjne();
        }
    }

    /**
     * 
     * @return boolean Zwraca czy jest kto¶ zalogowany czy nie
     */
    public function czy_zalogowany() {
        return (bool) $_SESSION['zalogowany'];
    }

    /**
     * 
     * @return boolean Zwraca czy jest kto¶ zalogowany czy nie
     */
    public function czy_jest_moderatorem() {
        return (bool) $_SESSION['jest_moderatorem'];
    }
        /*
     *  inicjalizuje/zeruje potrzebne do działania zmienne sesyjne
     */
    public function zeruj_zmienne_sesyjne() {
        $_SESSION['zalogowany'] = 0;
        $_SESSION['dane_poprawne'] = 0;
        $_SESSION['jest_moderatorem'] = 0;
        $_SESSION['uzytkownik_id'] = 0;
    }

    /*
     * @param string $nazwa_elementu nazwa klucza w wybranej tablicy lub zmienna której istnienie chcemy ustalić
     * @param string $nazwa_tablicy nazwa tablicy globalnej, której chcemy użyć
     * @return integer zwraca 1 gdy istnieje 0 gdy nie istnieje
     */
    public function sprawdz_istnienie($nazwa_elementu, $nazwa_tablicy = '') {
        switch ($nazwa_tablicy) {
            case 'post': {
                    $czy_istnieje = isset($_POST[$nazwa_elementu]) ? 1 : 0;
                    break;
                }

            case 'get': {
                    $czy_istnieje = isset($_GET[$nazwa_elementu]) ? 1 : 0;
                    break;
                }

            case 'session': {
                    $czy_istnieje = isset($_SESSION[$nazwa_elementu]) ? 1 : 0;
                    break;
                }

            default : {
                    $czy_istnieje = isset($nazwa_elementu) ? 1 : 0;
                    break;
                }
        }
        return $czy_istnieje;
    }

}
