<?php

/**
 * @author Piotr
 * @version 1.0
 */
class kontroler__forum {

    /**
     * 
     * @var mixed $model
     */
    private $model;
    
    /**
     *
     * @var widok__widok $widok
     */
    private $widok;
    
    /**
     *
     * @var model__uzytkownik $uzytkownik
     */
    private $uzytkownik;
    
    /**
     * @var model__uzytkownik $dane_uzytkownika
     */
    private $dane_uzytkownika;
    
    /**
     *
     * @var string $nazwa_szablonu
     */
    private $nazwa_szablonu;

    /**
     * konstruktor klasy
     * @param string $co_pokazac
     */
    public function __construct($co_pokazac) {
        $this->widok = new widok__widok();
        $this->uzytkownik = new model__uzytkownik();
        if ($co_pokazac == 'posty') {
            $this->model = new model__post();
            $this->nazwa_szablonu = 'posty.tpl';
        } else {
            $this->model = new model__watek();
            $this->nazwa_szablonu = 'watki.tpl';
        }
        $warunek = isset($_SESSION['uzytkownik_id']) && $_SESSION['uzytkownik_id'];
        if ($warunek) {
            $this->dane_uzytkownika = $this->uzytkownik->pobierz_info_o_uzytkowniku($_SESSION['uzytkownik_id']);
        }

        $this->ustaw_zmienne_sesji();

        $this->laduj_widok();
    }

    /**
     *  ładuje odpowiedni widok
     */
    public function laduj_widok() {

        if (!empty($this->uzytkownik)) {
            if(isset($_SESSION['zalogowany'])) {
                $this->widok->szablon->assign('zalogowany', $_SESSION['zalogowany']);
            } else {
                $this->widok->szablon->assign('zalogowany', 0);
                $_SESSION['zalogowany'] = 0;
            }
            if(isset($_SESSION['jest_moderatorem'])) {
                $this->widok->szablon->assign('czy_jest_moderatorem', $_SESSION['jest_moderatorem']);
            } else {
                $this->widok->szablon->assign('czy_jest_moderatorem',0);
                $_SESSION['jest_moderatorem'] = 0;
            }
            if ($this->model instanceof model__watek) {
                $this->pobierz_i_wyswietl_watki($this->dane_uzytkownika['zalogowany'], $this->dane_uzytkownika['jest_moderatorem']);
            } else {
                $this->pobierz_i_wyswietl_posty($this->dane_uzytkownika['zalogowany'], $this->dane_uzytkownika['jest_moderatorem']);
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

    /**
     * pobiera i wyswietla posty 
     * @param integer $zalogowany
     * @param integer $czy_moderator
     */
    public function pobierz_i_wyswietl_posty($zalogowany = 0, $czy_moderator = 0) {
        $this->czy_dodac_posty();
        $this->czy_aktualizowac_posty();
        $this->model->filtruj_posty($zalogowany, $czy_moderator);
        $watek_id = (int)$_GET['posty'];
        $this->widok->szablon->assign('watek_id', $watek_id);
        $this->widok->szablon->assign('tytul_watku', $this->model->pobierz_tytul_watku($watek_id));
        $this->widok->szablon->assign('posty', $this->model->posty);
        $this->widok->szablon->display('widoki/posty.tpl');
    }

    /**
     * pobiera i wyswietla watki 
     * @param integer $zalogowany
     * @param integer $czy_moderator
     */
    public function pobierz_i_wyswietl_watki($zalogowany = 0, $czy_moderator = 0) {
        $this->czy_dodac_watki();
        $this->czy_aktualizowac_watki();
        $this->model->filtruj_watki($zalogowany, $czy_moderator);
        $this->widok->szablon->assign('watki', $this->model->watki);
        $this->widok->szablon->display('widoki/watki.tpl');
    }

    /**
     * sprawdza czy trzeba aktualizować posty
     */
    public function czy_aktualizowac_posty() {
        $potwierdzenie = isset($_POST['post_id']) && $_SESSION['czy_jest_moderatorem'] && isset($_POST['zmiana_statusu_postu']);

        if ($potwierdzenie) {
            $status_postu = $_POST['zmiana_statusu_postu'];
            $status_postu = $this->model->zabezpiecz($status_postu);
            $post_id = (int)$_POST['post_id'];
            $this->model->zmien_status_postu($status_postu, $post_id);
        }
    }

    /**
     * sprawdza czy dodać post 
     */
    public function czy_dodac_posty() {
        $warunek = isset($_POST['zawartosc']);
        if ($warunek) {
            $post = (string)$_POST['zawartosc'];
            $post = $this->model->zabezpiecz($post);
            if (!empty($post)) {
                $watek_id = (int)$_POST['watek_id'];
                $this->model->dodaj_post($post, $watek_id, $_SESSION['uzytkownik_id']);
            }
        }
    }

    /**
     * sprawdza czy dodać wątek 
     */
    public function czy_dodac_watki() {
        $warunek = isset($_POST['tytul_watku']);
        if ($warunek) {
            $tytul_watku = $_POST['tytul_watku'];
            $tytul_watku = $this->model->zabezpiecz($tytul_watku);
            if (!empty($tytul_watku)) {
                $this->model->dodaj_watek($tytul_watku, $_SESSION['uzytkownik_id']);
            } else {
                $this->widok->laduj_inny_szablon('niewlasciwe_dane');
            }
        }
    }

    /**
     * sprawdza czy trzeba aktualizować wątki
     */
    public function czy_aktualizowac_watki() {
        //sprawdzamy cz jest moderatorem i czy chce zmienic status watku
        $czy_aktualizowac_watki = (isset($_POST['watek_id']) && $_SESSION['czy_jest_moderatorem'] && isset($_POST['zmiana_statusu_watku']));

        if ($czy_aktualizowac_watki) {
            $status_watku = (string)$_POST['zmiana_statusu_watku'];
            $watek_id = (int)$_POST['watek_id'];
            $this->model->zmien_status_watku($status_watku, $watek_id);
        }
    }

    /**
     * pobiera zmienne do logowania z bazy danych
     */
    public function ustaw_zmienne_sesji() {
        if (!empty($this->uzytkownik)) {
            $_SESSION['zalogowany'] = $this->dane_uzytkownika['zalogowany'];
            $_SESSION['czy_jest_moderatorem'] = $this->dane_uzytkownika['jest_moderatorem'];
            $_SESSION['uzytkownik_id'] = $this->dane_uzytkownika['uzytkownik_id'];
        } else {
            $_SESSION['zalogowany'] = 0;
            $_SESSION['czy_jest_moderatorem'] = 0;
            $_SESSION['uzytkownik_id'] = 0;
        }
    }

}
