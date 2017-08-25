{if $mode == 'admin'}
	{include file='header.tpl' title='Eventregistratie Admin' mode='admin'}
{/if}
<form method='post' action='{if $mode == admin}eventregadmin{else}event{/if}.php'>
	<p>
		<input type='hidden' name='id' value='{$event.id}' />
		<input type='hidden' name='mode' value='submit' />
		{if $mode == 'admin'}
			<input type='hidden' name='ticketnumber' value='{$registratie.ticketnumber}' />
			<input type='hidden' name='ticketid' value='{$registratie.ticketid}' />
		{/if}
	</p>
	<table>
		{if $mode == 'admin'}
			<tr><td>Event:</td><td>{$event.name}</td></tr>
			<tr><td>Naam:</td><td>{$registratie.name}</td></tr>
			<tr><td>E-mailadres:</td><td>{$registratie.emailaddress}</td></tr>
			<tr><td>Registratienummer:</td><td>{$registratie.ticketnumber}</td></tr>
			<tr><td>Volgnummer:</td><td>{$registratie.ticketid}</td></tr>
		{else}
			<tr><td>Registratienummer:</td><td><input type='text' name='ticketnumber' maxlength='8' size='8' /></td></tr>
			<tr><td>E-mailadres:</td><td><input type='text' name='emailaddress' /></td></tr>
		{/if}
		
		{section name='formulier' loop=$event.fields}
			{if $event.fields[formulier].type == 'text'}
				<tr><td>{$event.fields[formulier].name|escape}:</td><td><input type='text' name='field{$smarty.section.formulier.index}'{if $mode == 'admin'} value='{$registratie.values[formulier]|escape}'{/if} /></td></tr>
			{/if}
			{if $event.fields[formulier].type == 'yesno'}
				<tr><td>{$event.fields[formulier].name|escape}:</td><td><input type='radio' name='field{$smarty.section.formulier.index}' value='y'{if $mode == 'admin' && $registratie.values[formulier] == 'y'} checked='checked'{/if} />Ja&nbsp;&nbsp;&nbsp;<input type='radio' name='field{$smarty.section.formulier.index}' value='n'{if $mode == 'admin' && $registratie.values[formulier] == 'n'} checked='checked'{/if} />Nee</td></tr>
			{/if}
			{if $event.fields[formulier].type == 'textarea'}
				<tr><td>{$event.fields[formulier].name|escape}:</td><td><textarea name='field{$smarty.section.formulier.index}' rows='10' cols='18'>{if $mode == 'admin'}{$registratie.values[formulier]|escape}{/if}</textarea></td></tr>
			{/if}
		{/section}
		
	</table>
	<p>
		<input type='submit' value='{if $mode == admin}Wijzig{else}Registreer{/if}' />
	</p>
</form>

{if $mode == 'admin'}
	{include file='footer.tpl' mode='admin'}	
{/if}