{foreach from=$posty item=wartosc}
   <pre id='post'>
       {$wartosc.autor} 
       {$wartosc.zawartosc}<br/>
       {$wartosc.posts_status}<br/>
       {$wartosc.post_id}
        
   </pre>    

        {if $czy_jest_moderatorem==1}       
        <div>
    
            <form action='.?nr_watku={$wartosc.topic_id}' method='post'>
    <select name="zmiana_statusu_postu">
	<option value="do_moderacji">Do moderacji</option>
        <option value="skasowany">Skasuj</option>
        <option value="ukryty">Ukryj</option>
        <option value="widoczny">Widoczny</option>
        
</select>
<input type='hidden' name="post_id" value='{$wartosc.post_id}'  />                
<input type='submit' value='zaloguj'  />
</form>
        </div>
            {/if}

           
   
{/foreach}


 {if $zalogowany==1}
     {foreach from=$posty item=wartosc}
         
            <form action='.?nr_watku={$wartosc.topic_id}' method='post' maxlength='120'>
    <textarea rows="4" cols="50" name='zawartosc'>
    Tu wpisz swój post!
    </textarea><br/>
  <input type='hidden' name="topic_id" value='{$wartosc.topic_id}'  /> 
<input type='submit' value='Dodaj Post!'  />
</form>
{break}
     
{/foreach}
{/if}

