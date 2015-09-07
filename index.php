<?php
require 'include/kontroler.php';

$kontroler= new kontroler();
$kontroler->pokaz_bledy();
echo var_dump($_POST);
echo var_dump($_SESSION);


if(!isset($_SESSION['zalogowany'])&&(!isset($_SESSION['dane_poprawne']))&&(!isset($_SESSION['jest_moderatorem']))){
    $kontroler->utworz_zmienne_sesyjne();
}
if(isset($_POST['wyloguj'])){
    if($_POST['wyloguj']){
        $kontroler->wyloguj();
    }
}

if($_SESSION['zalogowany']==0){
    if(isset($_POST['login'])&&isset($_POST['haslo'])){
        $login=$_POST['login'];
        $haslo=$_POST['haslo'];
        $kontroler->zaloguj($login, $haslo);
    }else{
    // ogarnaæ to jako¶ widokiem 
    $kontroler->widok->laduj_formularz("logowanie");
    }
}

if(($_SESSION['zalogowany']==0)&&($_SESSION['dane_poprawne']==0)&&(isset($_POST['login'])==1)){
    echo 'wpisano niepoprawne dane';
    $kontroler->widok->laduj_formularz("logowanie");
}

 // ogarnaæ to jako¶ widokiem 
if($_SESSION['zalogowany']==1){
    $kontroler->widok->laduj_formularz("wylogowanie");
 
}
$kontroler->czy_istnieje['get_nr_watku']=isset($_GET['nr_watku'])?1:0;
$warunek_watki='';

if(!$kontroler->czy_zalogowany()||!$kontroler->czy_jest_moderatorem()){
     $warunek_watki.="where topic_status!='skasowany'"
             . "and topic_status!='do_moderacji '"
             . "and topic_status!='ukryty' ";
}  else {
    $warunek_watki='';
}

$kontroler->model->watki=$kontroler->model->wez_dane('*','watek',$warunek_watki);


  if(isset($_POST['post_id'])){
    if(isset($_POST['zmiana_statusu_postu'])){
        if($kontroler->czy_jest_moderatorem()){
        $kontroler->model->baza->aktualizuj('post','posts_status',$_POST['zmiana_statusu_postu'],"post_id='".$_POST['post_id']."'");
    }
        }
        
    
        }
        if(isset($_POST['zawartosc'])){
        
        $kontroler->model->baza->dodaj_wartosci('post','zawartosc,topic_id,posts_status,autor','"'.$_POST['zawartosc'].'",'.$_POST['topic_id'].',"widoczny",'.$_SESSION['uzytkownik_id']);
        
        }

if($kontroler->czy_istnieje['get_nr_watku']){
    $nr_watku=$_GET['nr_watku'];
    $warunek_posty="where topic_id=".$nr_watku." ";
    if(!$kontroler->czy_zalogowany()){
     $warunek_posty.=" and posts_status!='skasowany'"
             . "and posts_status!='do_moderacji '"
             . "and posts_status!='ukryty' ";
    }elseif(!$kontroler->czy_jest_moderatorem()){
     $warunek_posty.=" and posts_status!='skasowany'"
             . "and posts_status!='do_moderacji '"
             . "and posts_status!='ukryty' ";   
    }
    $kontroler->model->posty=$kontroler->model->wez_dane('*','post',$warunek_posty);
    $warunek_postu='';
}


$kontroler->model->przerob_watki();
$kontroler->model->przerob_posty();
$kontroler->widok->laduj_naglowek();
$kontroler->widok->laduj_widok($kontroler->model->watki,'watki',$kontroler->czy_jest_moderatorem(),$kontroler->czy_zalogowany());
$kontroler->widok->laduj_widok($kontroler->model->posty,'posty',$kontroler->czy_jest_moderatorem(),$kontroler->czy_zalogowany());

//echo $kontroler->model->baza->polecenie;
//jako¶ inaczej wypisywanie wszystkiego zrobiæ




//print_r($_SESSION);
//print_r($_POST);


        
/*   
foreach($kontroler->model->watki as $watek){
    echo $watek['topic_title']."  ";
    echo $watek['topic_status']."<br/>";
}*/


