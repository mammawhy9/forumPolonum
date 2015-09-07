{foreach from=$watki item=wartosc}
   <div id='watek'>
    <a href='.?nr_watku={$wartosc.topic_id}'>
        {$wartosc.topic_title}
    </a> 
        {$wartosc.topic_status} <br/>
   </div>    
{/foreach}
