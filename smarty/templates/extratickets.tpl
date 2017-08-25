{include file='header.tpl' title='Registratie'}

{if $invalid}
	<p class='invalid'>
	Je dient alle velden correct in te vullen om extra kaartjes te registreren. Verbeter de met rood aangegeven velden en probeer het opnieuw.
	</p>
{/if}

<p>
	Omdat de kaartjes op naam staan en we graag willen weten hoe onze doelgroep eruit ziet, willen we je vragen om de volgende gegevens door te geven voor de kaartjes die je extra hebt besteld. Kaartje 1 staat automatisch op jouw eigen naam.
</p>

<p>
	<b>Gegevens kaartje 1:</b>
</p>
<form method='post' action='./registratie.php'>
<p>
	<input type='hidden' name='mode' value='extrasubmit' />
<table>
	<tr><td>Voornaam:</td><td><input type='text' name='firstname0' disabled='disabled' value='{$user.firstname|escape}' /></td></tr>
	<tr><td>Tussenvoegsels:</td><td><input type='text' name='prefixes0' disabled='disabled' value='{$user.prefixes|escape}' /></td></tr>
	<tr><td>Achternaam:</td><td><input type='text' name='surname0' disabled='disabled' value='{$user.surname|escape}' /></td></tr>
	<tr><td>Geboortedatum:</td><td><input type='text' name='dob10' maxlength='2' size='2' disabled='disabled' value='{$user.dob1|escape}' />-<input type='text' name='dob20' maxlength='2' size='2' disabled='disabled' value='{$user.dob2|escape}' />-<input type='text' name='dob30' maxlength='4' size='4' disabled='disabled' value='{$user.dob3|escape}' /></td></tr>
	<tr><td>Geslacht:</td><td><input type='radio' name='gender0' value='Man'{if $user.gender == 'Man'} checked='checked'{/if} disabled='disabled' />Man&nbsp;&nbsp;&nbsp;<input type='radio' name='gender' value='Vrouw'{if $user.gender == 'Vrouw'} checked='checked'{/if} disabled='disabled' />Vrouw</td></tr>
</table>

{section name='extratickets' loop=$user.amount+1 start=2}
	<br /><br />
	<p>
		<b>Gegevens kaartje {$smarty.section.extratickets.index}:</b>
	</p>
	<table>
		<tr><td{if $inv[extratickets].firstname} class='invalid'{/if}>Voornaam:</td><td><input type='text' name='firstname{$smarty.section.extratickets.index}' value='{$tickets[extratickets].firstname|escape}' /></td></tr>
		<tr><td>Tussenvoegsels:</td><td><input type='text' name='prefixes{$smarty.section.extratickets.index}' value='{$tickets[extratickets].prefixes|escape}' /></td></tr>
		<tr><td{if $inv[extratickets].surname} class='invalid'{/if}>Achternaam:</td><td><input type='text' name='surname{$smarty.section.extratickets.index}' value='{$tickets[extratickets].surname|escape}' /></td></tr>
		<tr><td{if $inv[extratickets].dob} class='invalid'{/if}>Geboortedatum:</td><td><input type='text' name='dob1{$smarty.section.extratickets.index}' value='{$tickets[extratickets].dob1|escape}' maxlength='2' size='2' />-<input type='text' name='dob2{$smarty.section.extratickets.index}' value='{$tickets[extratickets].dob2|escape}' maxlength='2' size='2' />-<input type='text' name='dob3{$smarty.section.extratickets.index}' value='{$tickets[extratickets].dob3|escape}' maxlength='4' size='4' /></td></tr>
		<tr><td{if $inv[extratickets].gender} class='invalid'{/if}>Geslacht:</td><td><input type='radio' name='gender{$smarty.section.extratickets.index}' value='Man'{if $tickets[extratickets].gender == 'Man'} checked='checked'{/if} />Man&nbsp;&nbsp;&nbsp;<input type='radio' name='gender{$smarty.section.extratickets.index}' value='Vrouw'{if $tickets[extratickets].gender == 'Vrouw'} checked='checked'{/if} />Vrouw</td></tr>
	</table>
{/section}


<p>
<input type='submit' value='Volgende' />
</p>
</form>

{include file='footer.tpl' mode='reg'}