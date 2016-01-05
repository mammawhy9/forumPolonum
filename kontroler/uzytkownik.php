<?php

/*
 * kontroler dla logowa nie
 *
 * @author piotr
 * @version 1.0
 */
class kontroler__uzytkownik {

    public $widok;
    public $model;
    public $dane_uzytkownika;

    public function __construct() {
        $this->widok = new widok__widok();
        $this->model = new model__uzytkownik();

        if (!isset($_SESSION['zalogowany'])) {
            $this->zeruj_zmienne_sesyjne();
        }
        $this->inicjuj();
    }

    /*
     * loguje/wylogowywuje uzytkownika
     */
    public function inicjuj() {
        $this->czy_rejestrowac();
        $this->czy_logowac();
        $this->wylogowanie();
        $this->logowanie();
        
        if ($_SESSION['zalogowany']) {
            $this->widok->laduj_formularz("wylogowanie");
            $this->dane_uzytkownika = $this->model->pobierz_info_o_uzytkowniku($_SESSION['uzytkownik_id']);
        }
    }

    /*
     * sprawdza czy konieczne jest zaladowanie formularza
     */
    public function czy_logowac() {
        $warunek_logowanie = (!$_SESSION['zalogowany']) && (!$_SESSION['dane_poprawne'])
            && (isset($_POST['login'])) && (isset($_POST['zarejestruj'])) ? 1 : 0;

        if ($warunek_logowanie) {
            $this->widok->laduj_formularz("logowanie");
        }
    }

    /*
     * sprawdza czy rejestrowac; jezeli tak to rejestruje
     */
    public function czy_rejestrowac() {
        $sprawdzenie = isset($_GET['zarejestruj']) && ($_GET['zarejestruj'])
            && isset($_POST['login_rejestracja']) && isset($_POST['login_rejestracja']);
        if ($sprawdzenie) {
            $login = $_POST['login_rejestracja'];

            if (!preg_match("/[\W]/", $login) && (!empty($login))) {
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
     * wylogowanie użytkownika przez wyzerowanie zmiennych sesyjnych i wpis do bazy
     */
    public function wylogowanie() {
        $warunek = isset($_POST['wyloguj']);
        if ($warunek) {
            $czy_wylogowac = (bool) $_POST['wyloguj'];
            if ($czy_wylogowac) {
                $this->model->zeruj_zmienne_sesyjne();
                $this->model->wyloguj($_SESSION['uzytkownik_id']);
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
        $warunek_rejestracji = $this->model->sprawdz_ilosc(" login='".$login."'")
            && !empty($login) && !empty($haslo);

        if (!$warunek_rejestracji) {
            $this->model->dodaj_wartosci(array('imie', 'nazwisko', 'login', 'haslo',
                'jest_moderatorem'),
                array($imie, $nazwisko,$login,crypt($haslo,CRYPT_BLOWFISH),0));
        } else {
            $this->widok->laduj_inny_szablon('niewlasciwe_dane');
            $this->model->zeruj_zmienne_sesyjne();
        }
    }

    /*
     * obsluga logowania
     */
    public function logowanie() {
        if (isset($_SESSION['zalogowany'])) {
            // jeżeli niezalogowany to sprawdzamy czy przez post dostalismy jakis login i haslo 
            if (!$_SESSION['zalogowany']) {
                $warunek = isset($_POST['login']) && isset($_POST['haslo']);
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

    /*
     * logowanie uzytkownika i nadawanie mu praw oraz pobieranie id uzytkownika
     * @param String $login Login do konta
     * @param String $haslo Hasło do konta
     */
    public function zaloguj($login, $haslo) {
        // sprawdzamy cz istnieje taki uzytkownik
        $czy_istnieje_uzytkownik = $this->model->sprawdz_ilosc(
            " login='".$login."'"." and haslo='".crypt($haslo, CRYPT_BLOWFISH)."'"
        );

        if ($czy_istnieje_uzytkownik) {
            $this->model->zaloguj($login, $haslo);
        } else {
            $this->widok->laduj_inny_szablon('niewlasciwe_dane');
            $this->widok->laduj_formularz("logowanie");
            $this->model->zeruj_zmienne_sesyjne();
        }
    }

}
