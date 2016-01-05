<?php


/*
 * obiekt zarzadzajacy cąłą stroną
 * @author Piotr Kowal <piotr.kowal@polskapress.pl>
 * @version 1.0
 */

class include__kontroler {
    /*
     * @var include__model $model 
     * @var include__widok $widok
     * @var 
     */

    public $model;
    public $widok;

    /**
     * obiekt zarzadzajacy cąłą stroną
     */
    public function __construct() {
        $this->model = new include__model();
        $this->widok = new include__widok();
    }

    /**
     * logowanie uzytkownika i nadawanie mu praw oraz pobieranie id uzytkownika
     * @param String $login Login do konta
     * @param String $haslo Hasło do konta
     */
    public function zaloguj($login, $haslo) {
        // sprawdzamy cz istnieje taki uzytkownik
        $czy_istnieje_uzytkownik = $this->model->sprawdz_ilosc(
            'uzytkownicy',
            "where login='".$login."'"
            ." and haslo='".sha1($haslo)."'"
        );

        if ($czy_istnieje_uzytkownik) {
            $_SESSION['zalogowany'] = 1;
            $_SESSION['dane_poprawne'] = 1;

            //sprawdzamy czy ma uprawnienia administratora
            $wynik_moderator = $this->model->czy_jest_moderatorem('where login="'.$login.'"');
            if ('1' == $wynik_moderator) {
                $_SESSION['jest_moderatorem'] = 1;
            } else {
                $_SESSION['jest_moderatorem'] = 0;
            }

            // pobieramy id uzytkownika
            $uzytkownik_id = $this->model->wez_dane(
                'uzytkownik_id', 'uzytkownicy',
                "where login='".$login."' and haslo='".sha1($haslo)."'"
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

    /*
     * filtruje wątki do wyświetlenia
     */
    public function filtruj_watki() {
        //jezeli nie jest zalogowany lub nie jest moderatorem, wyswietlamyh tylko watki ze statusem 'widoczny'
        $warunek_watki = '';
        if (!$this->czy_zalogowany() || !$this->czy_jest_moderatorem()) {
            $warunek_watki .= "where status!='skasowany'"
                ."and status!='do_moderacji '"
                ."and  status!='ukryty' ";
        } else {
            $warunek_watki = '';
        }
        $this->model->watki = $this->model->wez_dane('*', 'watki',
            $warunek_watki);
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
            $this->model->posty = $this->model->wez_dane(
                'pk_posty.post_id,pk_posty.zawartosc,'
                .'pk_posty.watek_id,pk_posty.status,'
                .'pk_uzytkownicy.login', 'posty',
                "left join pk_uzytkownicy on "
                ."pk_posty.autor=pk_uzytkownicy.uzytkownik_id ".$warunek_posty);
            $warunek_postu = '';
        }
    }
    
    /*
     * sprawdza czy dodać wątek 
     */
    public function czy_dodac_watki() {
        if ($this->sprawdz_istnienie('tytul_watku', 'post')) {
            $tytul_watku = (string) $_POST['tytul_watku'];
            if (!empty($tytul_watku)) {
                $this->model->baza->dodaj_wartosci('watki', 'tytul,autor',
                    '"'.$tytul_watku.'",'.$_SESSION['uzytkownik_id']
                );
            } else {
                $this->widok->laduj_inny_szablon('niewlasciwe_dane');
            }
        }
    }

    /*
     * sprawdza czy dodać post 
     */
    public function czy_dodac_posty() {
        if ($this->sprawdz_istnienie('zawartosc', 'post')) {
            $post = (string) $_POST['zawartosc'];
            if (!empty($post)) {
                $watek_id = (int) $_POST['watek_id'];
                $this->model->baza->dodaj_wartosci(
                    'posty', 'zawartosc,watek_id,autor',
                    '"'.$post.'",'.$watek_id
                    .','.$_SESSION['uzytkownik_id']);
            } else {
                $this->widok->laduj_inny_szablon('niewlasciwe_dane');
            }
        }
    }

    /*
     * wylogowanie użytkownika przez wyzerowanie zmiennych sesyjnych
     */
    public function wylogowanie() {
        if ($this->sprawdz_istnienie('wyloguj', 'post')) {
            $czy_wylogowac = (bool) $_POST['wyloguj'];
            if ($czy_wylogowac) {
                $this->zeruj_zmienne_sesyjne();
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
        $warunek_rejestracji = !$this->model->sprawdz_ilosc(
                'uzytkownicy', "where login='".$login."'") && !empty($login) && !empty($haslo)
                ? 1 : 0;
        if ($warunek_rejestracji) {
            $this->model->baza->dodaj_wartosci(
                'uzytkownicy', 'imie,nazwisko,login,haslo,jest_moderatorem',
                "'".$imie."','".$nazwisko."','".$login."','".$haslo."',0");
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

    /*
     * sprawdza czy trzeba aktualizować wątki
     */
    public function czy_aktualizowac_watki() {
        //sprawdzamy cz jest moderatorem i czy chce zmienic status watku
        $czy_aktualizowac_watki = ($this->sprawdz_istnienie('watek_id', 'post') &&
            $this->czy_jest_moderatorem()
            && $this->sprawdz_istnienie('zmiana_statusu_watku', 'post')) ? 1 : 0;

        if ($czy_aktualizowac_watki) {
            $status_watku = (string) $_POST['zmiana_statusu_watku'];
            $watek_id = (int) $_POST['watek_id'];
            $this->model->baza->aktualizuj(
                'watki', 'status', $status_watku, "watek_id='".$watek_id."'");
        }
    }

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
            $this->model->baza->aktualizuj(
                'posty', 'status', $status_postu, "post_id='".$post_id."'"
            );
        }
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
        $sprawdzenie = $this->sprawdz_istnienie('zarejestruj', 'get') && $this->sprawdz_istnienie('imie',
                'post');

        if ($sprawdzenie) {
            $imie = htmlspecialchars($_POST['imie']);
            $nazwisko = htmlspecialchars($_POST['nazwisko']);
            $login = htmlspecialchars($_POST['login_rejestracja']);
            $haslo = htmlspecialchars($_POST['haslo_rejestracja']);
            $this->rejestruj($imie, $nazwisko, $login, $haslo);
        }
    }

}
