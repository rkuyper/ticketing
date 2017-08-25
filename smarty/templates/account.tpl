{include file='header.tpl' title='Registratiegegevens'}
{if $mode == 'geen'}
<form method='post' action='account.php'>
		<p>
		Op deze pagina kun je inloggen om te bekijken of jouw registratie betaald is en voor welke activiteiten je geregistreerd staat. Registratienummer vergeten? Neem dan <a href='./contact.php?recipient=5'>contact</a> met ons op.
		<input type='hidden' name='mode' value='submit' /></p>
		<table>
		<tr><td>Registratienummer:</td><td><input type='text' maxlength='8' size='8' name='ticketnumber' /></td></tr>
		<tr><td>E-mailadres:</td><td><input type='text' name='emailaddress' /></td></tr>
		</table><p><input type='submit' value='Log in' /></p></form>

{elseif $mode == 'invalid'}
	<p>Het opgegeven registratienummer is incorrect of het e-mailadres komt niet overeen met degene die bij de registratie gebruikt is. Ga terug en verbeter dit.</p>

{elseif $mode == 'valid'}
	<p>
		Registratiegegevens voor {$ticketnumber}:
	</p>
	<p>
		Aantal kaartjes: {$aantaltickets}
		{section name=stickets loop=$tickets}
			<br />Ticket {$smarty.section.stickets.index+1}: {$tickets[stickets].name|escape}
		{/section}
	</p>
	<p>
		Status: <b>{if $tickets[0].state == 'Betaald'}betaald{else}niet betaald{/if}</b>
	</p>

	{if $tickets[0].state == 'Betaald'}
		<p>
			<a href='./ticket.php?n={$ticketnumber}&amp;h={$hash}'>Download kaartje(s)</a>
		</p>
	{/if}
	{if $events|@count > 0}
		<p>
			Je hebt je geregistreerd voor de volgende activiteiten (events):
		</p>
		<ul>
		{section name='regevents' loop=$events}
			{assign var=temp value=$events[regevents].ticketid-1}
			<li>{$tickets.$temp.name|escape} - <a href='./event.php?id={$events[regevents].eventid}'>{$events[regevents].eventname|escape}</a></li>
		{/section}
		</ul>
	{/if}
{/if}

{include file='footer.tpl' mode='reg'}