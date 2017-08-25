{include file='header.tpl' title='Registratiebevestiging'}

{if $mode == 'conbevestigd'}
<p>
Jouw e-mailadres is bevestigd! Als het goed is heb je jouw kaartje reeds van ons ontvangen, maar voor de zekerheid is er zojuist een kopie van jouw kaartje per e-mail naar jou verstuurd. Als je deze na enkele uren niet hebt ontvangen (en je zeker weet dat deze niet in jouw spam-folder terecht is gekomen) kun je het beste <a href='./contact.php?recipient=5'>contact</a> met ons opnemen.
</p>
{elseif $mode == 'bevestigd'}
<p>
Jouw e-mailadres is bevestigd! Er is zojuist een e-mail met betalingsinformatie en jouw unieke registratienummer naar jou verstuurd. Als je deze na enkele uren niet hebt ontvangen (en je zeker weet dat deze niet in jouw spam-folder terecht is gekomen) kun je het beste <a href='./contact.php?recipient=5'>contact</a> met ons opnemen.
</p>
{elseif $mode == 'reedsbevestigd'}
<p>
Het e-mailadres behorende bij deze registratie is reeds bevestigd.
</p>
{else}
<p>
Het opgegeven registratienummer is ongeldig. Waarschijnlijk betekent dit dat je niet binnen 24 uur op de link geklikt hebt. Je kunt je dan het beste opnieuw registreren. Als dit niet het geval is kun je het beste <a href='./contact.php?recipient=5'>contact</a> met ons opnemen.
</p>
{/if}

{include file='footer.tpl'}