<?php
require 'include/kontroler.php';

$kontroler= new kontroler();
$kontroler->pokaz_bledy();
$kontroler->model->wez_dane('watek');
echo $kontroler->model->baza->polecenie;
//jako¶ inaczej wypisywanie wszystkiego zrobiæ
//$kontroler->widok->laduj_watki($kontroler->model->watki);
if($_POST['wyloguj']){
    $kontroler->wyloguj();
}
if($_SESSION['zalogowany']==0){

if(isset($_POST['login'])&&isset($_POST['haslo'])){
    $login=$_POST['login'];
    $haslo=$_POST['haslo'];
 
    $kontroler->zaloguj($login, $haslo);

}else{
    // ogarnaæ to jako¶ widokiem 
    require 'widoki/formularz_logowania.tpl';
}
//print_r($_SESSION);
 //   print_r($_POST);

}
 // ogarnaæ to jako¶ widokiem 
if($_SESSION['zalogowany']==1){
    echo "<form action='.' method='post'><input type='hidden' value='true' name='wyloguj'/><input type='submit' value='Wyloguj'/></form>";
}

  //  print_r($_SESSION);
 //   print_r($_POST);
    
           
  
        
/*   
foreach($kontroler->model->watki as $watek){
    echo $watek['topic_title']."  ";
    echo $watek['topic_status']."<br/>";
}*/


