<?php
/*
 * @author Piotr
 * @version 1.0
 */
class kontroler__forum {

    private $model;
    private $widok;
    private $uzytkownik;
    private $nazwa_szablonu;
    
    public function __construct($co_pokazac) {


        $this->widok = new widok__widok();
        if ($co_pokazac == 'posty') {
            $this->model = new model__post();
            $this->nazwa_szablonu = 'posty.tpl';
        } else {
            $this->model = new model__watek();
            $this->nazwa_szablonu = 'watki.tpl';
        }
        if ($_SESSION['uzytkownik_id']) {
            $this->uzytkownik = $this->model->pobierz_info_o_uzytkowniku($_SESSION['uzytkownik_id']);
        }

        $this->ustaw_zmienne_sesji();

        $this->inicjuj();
       
    }

    /*
     * wyswietla wybrane info
     */
    public function inicjuj() {

        if (!empty($this->uzytkownik)) {
            $this->widok->szablon->assign('zalogowany', $_SESSION['zalogowany']);
            $this->widok->szablon->assign('czy_jest_moderatorem',
                $_SESSION['jest_moderatorem']);
            if ($this->model instanceof model__watek) {
                $this->pobierz_i_wyswietl_watki($this->uzytkownik['zalogowany'],
                    $this->uzytkownik['jest_moderatorem']);
            } else {
                $this->pobierz_i_wyswietl_posty($this->uzytkownik['zalogowany'],
                    $this->uzytkownik['jest_moderatorem']);
            }
        } else {
            $this->widok->szablon->assign('zalogowany', 0);
            $this->widok->szablon->assign('czy_jest_moderatorem', 0);
            if ($this->model instanceof model__watek) {
                $this->pobierz_i_wyswietl_watki();
            } else {
                $this->pobierz_i_wyswietl_posty();
            }
        }
    }

    /*
     * pobiera i wyswietla posty 
     * @param int $zalogowany
     * @param int $czy_moderator
     */
    public function pobierz_i_wyswietl_posty($zalogowany=0, $czy_moderator=0) {
        $this->czy_dodac_posty();
        $this->czy_aktualizowac_posty();
        $this->model->filtruj_posty($zalogowany, $czy_moderator);
        $watek_id = (int) $_GET['posty'];
        $this->widok->szablon->assign('watek_id', $watek_id);
        $this->widok->szablon->assign('tytul_watku',
            $this->model->pobierz_tytul_watku($_GET['posty']));
        $this->widok->szablon->assign('posty', $this->model->posty);
        $this->widok->szablon->display('widoki/posty.tpl');
    }

    /*
     * pobiera i wyswietla watki 
     * @param int $zalogowany
     * @param int $czy_moderator
     */
    public function pobierz_i_wyswietl_watki($zalogowany=0, $czy_moderator=0) {
        $this->czy_dodac_watki();
        $this->czy_aktualizowac_watki();
        $this->model->filtruj_watki($zalogowany, $czy_moderator);
        $this->widok->szablon->assign('watki', $this->model->watki);
        $this->widok->szablon->display('widoki/watki.tpl');
    }

    /*
     * sprawdza czy trzeba aktualizować posty
     */
    public function czy_aktualizowac_posty() {
        $potwierdzenie = isset($_POST['post_id'])
            && $_SESSION['czy_jest_moderatorem']
            && isset($_POST['post_id']) ? 1 : 0;

        if ($potwierdzenie) {
            $status_postu = (string) $_POST['zmiana_statusu_postu'];
            $post_id = (int) $_POST['post_id'];
            $this->model->zmien_status_postu($status_postu, $post_id);
        }
    }

    /*
     * sprawdza czy dodać post 
     */
    public function czy_dodac_posty() {
        $warunek = isset($_POST['zawartosc']);
        if ($warunek) {
            $post = htmlspecialchars($_POST['zawartosc']);
            $post = addslashes($post);
            if (!empty($post)) {
                $watek_id = (int) $_POST['watek_id'];
                $this->model->dodaj_post($post, $watek_id,
                    $_SESSION['uzytkownik_id']);
            }
        }
    }

    /*
     * sprawdza czy dodać wątek 
     */
    public function czy_dodac_watki() {
        if (isset($_POST['tytul_watku'])) {
            $tytul_watku = htmlspecialchars($_POST['tytul_watku']);
            if (!empty($tytul_watku)) {
                $this->model->dodaj_watek($tytul_watku,
                    $_SESSION['uzytkownik_id']);
            } else {
                $this->widok->laduj_inny_szablon('niewlasciwe_dane');
            }
        }
    }

    /*
     * sprawdza czy trzeba aktualizować wątki
     */
    public function czy_aktualizowac_watki() {
        //sprawdzamy cz jest moderatorem i czy chce zmienic status watku
        $czy_aktualizowac_watki = (isset($_POST['watek_id']) && $_SESSION['czy_jest_moderatorem']
            && isset($_POST['zmiana_statusu_watku'])) ? 1 : 0;

        if ($czy_aktualizowac_watki) {
            $status_watku = (string)$_POST['zmiana_statusu_watku'];
            $watek_id = (int) $_POST['watek_id'];
            $this->model->zmien_status_watku($status_watku, $watek_id);
        }
    }

    /*
     * pobiera zmienne do logowania z bazy danych
     */
    public function ustaw_zmienne_sesji() {
        if (!empty($this->uzytkownik)) {
            $_SESSION['zalogowany'] = $this->uzytkownik['zalogowany'];
            $_SESSION['czy_jest_moderatorem'] = $this->uzytkownik['jest_moderatorem'];
            $_SESSION['uzytkownik_id'] = $this->uzytkownik['uzytkownik_id'];
        } else {
            $_SESSION['zalogowany'] = 0;
            $_SESSION['czy_jest_moderatorem'] = 0;
            $_SESSION['uzytkownik_id'] = 0;
        }
    }

}
