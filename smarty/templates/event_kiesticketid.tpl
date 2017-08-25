{include file='header.tpl' title=$event.name}
<p>
	Op dit registratienummer is meer dan één kaartje besteld. Kies uit onderstaande lijst wie er voor deze activiteit ingeschreven wil worden:
</p>
<form method='post' action='event.php'>
<p>
	<input type='hidden' name='id' value='{$event.id}' />
	<input type='hidden' name='mode' value='submit' />
	{section name=stickets loop=$tickets}
		<input type='radio' name='ticketid' value='{$tickets[stickets].id}' /> {$tickets[stickets].name}<br />
	{/section}
</p>
<input type='submit' value='Registreer' />
</form>

{include file='footer.tpl'}