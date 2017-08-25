{include file='header.tpl' title='Registratie'}
<p>
	We hebben de volgende gegevens van jou ontvangen:
</p>
<table>
	<tr><td>Voornaam:</td><td>{$user.firstname|escape}</td></tr>
	<tr><td>Tussenvoegsels:</td><td>{$user.prefixes|escape}</td></tr>
	<tr><td>Achternaam:</td><td>{$user.surname|escape}</td></tr>
	<tr><td>Straat + huisnummer:</td><td>{$user.address|escape}</td></tr>
	<tr><td>Postcode:</td><td>{$user.postalcode|escape}</td></tr>
	<tr><td>Woonplaats:</td><td>{$user.city|escape}</td></tr>
	<tr><td>Land:</td><td>{$user.country|escape}</td></tr>
	<tr><td>Geboortedatum:</td><td>{$user.dob1|escape}-{$user.dob2|escape}-{$user.dob3|escape}</td></tr>
	<tr><td>Geslacht:</td><td>{$user.gender|escape}</td></tr>
	<tr><td>E-mailadres:</td><td>{$user.emailaddress|escape}</td></tr>
</table>
<p>Je hebt aangegeven <b>akkoord</b> te gaan met onze <a href='index.php?id=12'>huisregels</a>.</p>

{if $user.amount > 1}
	<hr />
	<p>
		<b>Gegevens kaartje 1:</b>
	</p>
	<table>
		<tr><td>Voornaam:</td><td>{$user.firstname|escape}</td></tr>
		<tr><td>Tussenvoegsels:</td><td>{$user.prefixes|escape}</td></tr>
		<tr><td>Achternaam:</td><td>{$user.surname|escape}</td></tr>
		<tr><td>Geboortedatum:</td><td>{$user.dob1|escape}-{$user.dob2|escape}-{$user.dob3|escape}</td></tr>
		<tr><td>Geslacht:</td><td>{$user.gender|escape}</td></tr>
	</table>

	{section name='extratickets' loop=$user.amount+1 start=2}
	<br /><br />
		<p>
			<b>Gegevens kaartje {$smarty.section.extratickets.index}:</b>
		</p>
		<table>
			<tr><td>Voornaam:</td><td>{$tickets[extratickets].firstname|escape}</td></tr>
			<tr><td>Tussenvoegsels:</td><td>{$tickets[extratickets].prefixes|escape}</td></tr>
			<tr><td>Achternaam:</td><td>{$tickets[extratickets].surname|escape}</td></tr>
			<tr><td>Geboortedatum:</td><td>{$tickets[extratickets].dob1|escape}-{$tickets[extratickets].dob2|escape}-{$tickets[extratickets].dob3|escape}</td></tr>
			<tr><td>Geslacht:</td><td>{$tickets[extratickets].gender|escape}</td></tr>
		</table>
	{/section}

{/if}

<form method='post' action='registratie.php'>
	<p>
	<input type='hidden' name='mode' value='submit2' />
	<input type='submit' value='Registreer' />
	</p>
</form>
<form method='post' action='registratie.php'>
	<p>
	<input type='hidden' name='mode' value='wijzig' />
	<input type='submit' value='Wijzig gegevens' />
	</p>
</form>

{include file='footer.tpl' mode='reg'}