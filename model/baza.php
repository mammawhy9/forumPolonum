<?php

/**
 * Klasa do obslugi bazy danych
 *
 * @author piotr
 * @version 1.0
 */
class model__baza {
    /**
     * Kontaktuje się z bazą danych
     * Dane do logowania bierze z konfiguracji.php
     *
     */
    public static function polacz_z_baza() {
        require 'model/konfiguracja.php';
        $nazwa_bazy_hosta = 'mysql:dbname='.$dbname.';host='.$hostname;
        try {
            $polaczenie_z_baza = new PDO(
                $nazwa_bazy_hosta, $username, $password,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
            );
        } catch (PDOException $e) {
            echo 'Connection failed: '.$e->getMessage();
        }
        return $polaczenie_z_baza;
    }

}
