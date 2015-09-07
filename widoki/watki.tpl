<html>
    <head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
   <title>{$tytul}</title>
    </head>
    
    <body>
        <div id="watki">
            {foreach from $watki item=wartosc } 
            <p>{$wartosc['tytul']}</p>
            <p>{$wartosc['status']}</p>
            {/foreach}
        </div>
    </body>
</html>