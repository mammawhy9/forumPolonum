{$tytul_watku}<br>
<a href="?watki=1">
  <button>Wróć do wątków</button>
</a>
{foreach from=$posty item=wartosc}
  <div>
    <p><strong>{$wartosc.login}</strong> </p>
    <p>{$wartosc.zawartosc} ->  {$wartosc.status}</p>
  </div>    
  {if $czy_jest_moderatorem==1}       
    <div>
      <form action=".?posty={$wartosc.watek_id}" method="post">
        <fieldset>
          <select name="zmiana_statusu_postu">
            <option value="do_moderacji">Do moderacji</option>
            <option value="skasowany">Skasuj</option>
            <option value="ukryty">Ukryj</option>
            <option value="widoczny">Widoczny</option>
          </select>
          <input type="hidden" name="post_id" value="{$wartosc.post_id}">
          <input type="submit" value="Zmień status" >
        </fieldset>    
      </form>
    </div>
  {/if}
{/foreach}

{if $zalogowany==1}
  {if isset($watek_id)}
    <form action=".?posty={$watek_id}" method="post" maxlength="120">
      <fieldset>
        <textarea rows="4" cols="50" name="zawartosc"></textarea><br>
        <input type="hidden" name="watek_id" value="{$watek_id}" > 
        <input type="submit" value="Dodaj Post!">
      </fieldset>
    </form>
  {/if}
{/if}
