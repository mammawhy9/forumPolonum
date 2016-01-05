{if $formularz=="logowanie"}
  <form action=".?zaloguj=1" method="post">
    <fieldset>
      <input type="text" name="login"/><br>
      <input type="password" name="haslo"><br>
      <input type="submit" value="zaloguj"  >
    </fieldset>
  </form>
  <form action=".?zarejestruj=1" method="post">
    <fieldset>
      <label for="login_rejestracja">Login:</label>
      <input type="text" name="login_rejestracja"><br>
      <label for="haslo_rejestracja">Hasło:</label>
      <input type="password" name="haslo_rejestracja"><br>
      <label for="imie">Imię:</label>
      <input type="text" name="imie">  <br>
      <label for="nazwisko">Nazwisko:</label>
      <input type="text" name="nazwisko">
      <input type="submit" value="Rejestruj">
    </fieldset>
  </form>
  <a href=".?watki=1"><button>Wróć do wątków</button></a>
{else}
  <form action=".?zaloguj=1" method="post">
    <fieldset>
      <input type="hidden" value="true" name="wyloguj">
      <input type="submit" value="Wyloguj">
    </fieldset>        
  </form>
  <a href=".?watki=1"><button>Wróć do wątków</button></a>

{/if}
