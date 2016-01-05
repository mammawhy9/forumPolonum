
{if $formularz=="logowanie"}
    <form action='.' method='post'>
        <fieldset>
            <input type='text' name='login'/><br>
            <input type='password' name='haslo'/><br>
            <input type='submit' value='zaloguj'  />
        </fieldset>
    </form>
    <form action='.?zarejestruj=1' method='post'>
        <fieldset>
        Login:    <input type='text' name='login_rejestracja'/><br>
        Haslo:    <input type='password' name='haslo_rejestracja'/><br>
        Imie:     <input type='text' name='imie'/>  <br>
        Nazwisko: <input type='text' name='nazwisko'/>
        <input type='submit' value='Rejestruj'  />
        </fieldset>
    </form>
{else}
    <form action='.' method='post'>
        <fieldset>
            <input type='hidden' value='true' name='wyloguj'/>
            <input type='submit' value='Wyloguj'/>
        </fieldset>        
    </form>
{/if}