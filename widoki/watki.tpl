{if $zalogowany==1}
  <form action=".?zaloguj=1" method="post">
    <fieldset>
      <input type="hidden" value="true" name="wyloguj">
      <input type="submit" value="Wyloguj">
    </fieldset>        
  </form>
{else}
<a href=".?zaloguj=1"><button>Zaloguj</button></a>

{/if}
{foreach from=$watki item=wartosc}
  <div id="watek">
    <a href=".?posty={$wartosc.watek_id}">
      {$wartosc.tytul}
    </a> 
    {$wartosc.status} 
    {if $czy_jest_moderatorem==1}       
      <form action=".?watki=1" method="post">
        <fieldset>
          <select name="zmiana_statusu_watku">
            <option value="do_moderacji">Do moderacji</option>
            <option value="skasowany">Skasuj</option>
            <option value="ukryty">Ukryj</option>
            <option value="widoczny">Widoczny</option>
          </select>
        <input type="hidden" name="watek_id" value="{$wartosc.watek_id}"  >                
        <input type="submit" value="Zmień"  >
        </fieldset>    
      </form>     
    {/if}<br>
  </div>    
{/foreach}

{if !isset($watek_id)}
  {if $czy_jest_moderatorem==1}
    <form action="." method="post" maxlength="120">
      <fieldset>
        <input type="text" name="tytul_watku"> 
        <input type="submit" value="Dodaj Wątek!"  >
      </fieldset>    
    </form>
  {/if}
{/if}
