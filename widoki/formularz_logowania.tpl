
{if $formularz=="logowanie"}
    <form action='.' method='post'>
    <input type='text' name='login'/><br/>
    <input type='password' name='haslo'/><br/>
    <input type='submit' value='zaloguj'  />
</form>
    {else}
    <form action='.' method='post'>
        <input type='hidden' value='true' name='wyloguj'/>
        <input type='submit' value='Wyloguj'/>
    </form>
    {/if}