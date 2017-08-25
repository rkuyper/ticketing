{if $mode == 'geen' || $mode == 'invalid' || $mode=='logout'}
{include file='header.tpl' title='Chibi Admin' mode='login'}
{elseif $mode == 'index'}
{include file='header.tpl' title='Chibi Admin' mode='admin'}
{/if}

{if $mode == 'geen'}
<form method='post' action='chibiadmin.php'>
		<p><input type='hidden' name='mode' value='submit' /></p>
		<table>
		<tr>
		<td>Gebruikersnaam:</td><td><input type='text' name='username' /></td>
		</tr>
		<tr>
		<td>Wachtwoord:</td><td><input type='password' name='password' /></td>
		</tr>
		</table>
		<p><input type='submit' value='Log in' /></p>
		</form>
{elseif $mode == 'invalid'}
	<p>De inloggegevens kloppen niet. Ga terug en verbeter dit.</p>

{elseif $mode == 'index'}
	<p>
	Welkom in Chibi Admin! Maak in het menu een keuze.
	</p>

{elseif $mode == 'logout'}
	<p>
		Je bent succesvol uitgelogd.
	</p>
{/if}

{if $mode == 'geen' || $mode == 'invalid' || $mode=='logout'}
{include file='footer.tpl' mode='login'}
{elseif $mode == 'index'}
{include file='footer.tpl' mode='admin'}
{/if}