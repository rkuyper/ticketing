{include file='header.tpl' title='Betalingsimport' mode='admin'}
{if $mode == 'submit'}
	{section name=transsec loop=$transactions}
		<p>
		{if $state[transsec] == 'p'}
			<span class='valid'>
			Herkend! Ticketnummer: {$foundticketnumber[transsec]}
		{/if}
		
		{if $state[transsec] == 'u'}
			<span class='invalid'>
			Betaald bedrag onjuist.
		{/if}
		
		{if $state[transsec] == 'r'}
			<span class='invalid'>
			Ticket reeds betaald.
		{/if}
		
		{if $state[transsec] == 'i'}
			<span class='invalid'>
			Ticketnummer ongeldig.
		{/if}
		
		{if $state[transsec] == 'n'}
			<span class='invalid'>
			Transactie niet herkend.
		{/if}
		
		<br />
		{$transactions[transsec]|escape}
		
		</span>
		</p>
	{/section}
	<a href='./import.php?mode=dopay'>Verwerk betalingen</a>

{elseif $mode == 'dopay'}
	Betalingen verwerkt.
	<table>
	{section name=paysec loop=$matchresults}
		{if $matchresults[paysec].valid}
			<tr><td>{$matchresults[paysec].date|escape}</td><td>8000 Omzet kaartverkoop</td><td>Registratienr. {$matchresults[paysec].ticketnumber|escape}</td><td>{$matchresults[paysec].amount|escape}</td></tr>
		{else}
			<tr><td> </td><td> </td><td>Onbekende transactie</td><td> </td></tr>
		{/if}
	{/section}
	</table>

{else}
	<form method='post' action='./import.php'>
		<input type='hidden' name='mode' value='submit' />
		<textarea name='transacties' class='big'></textarea>
		<input type='submit' value='Verstuur' />
	</form>
{/if}

{include file='footer.tpl' mode='admin'}