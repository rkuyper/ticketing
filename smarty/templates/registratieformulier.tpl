{include file='header.tpl' title='Registratie'}

{if $invalid}
	<p class='invalid'>
	Je dient alle velden correct in te vullen om jezelf te registreren. Verbeter de met rood aangegeven velden en probeer het opnieuw.
	</p>
{/if}

<form action='registratie.php' method='post'>
	<p>
	<b>Heb je reeds een kaartje gekocht, maar ben je jouw kaartje kwijt of heb je deze nooit ontvangen? Klik dan <a href='./ticketstatus.php'>hier</a>.</b>
	</p>
	<p>
	Om Chibicon 2010 te bezoeken dien je vooraf een kaartje te kopen. We zullen waarschijnlijk geen kaartjes aan de deur verkopen! Je kunt jezelf registreren voor Chibicon door het onderstaande formulier in te vullen. Daarna ontvang je vanzelf per e-mail de details over de verdere werkwijze rond de betaling en het inchecken.
	</p>
	<p>
	Vanaf 10 april kost een kaartje <b>€17,50</b>. Als je meerdere kaartjes wilt bestellen, kun je dit onderaan de pagina aangeven.
	<input type='hidden' name='id' value='reg' />
	<input type='hidden' name='mode' value='submit' />
	</p>

<table>
	<tr><td{if $inv.firstname} class='invalid'{/if}>Voornaam:</td><td><input type='text' name='firstname' value='{$user.firstname|escape}' /></td></tr>
	<tr><td>Tussenvoegsels:</td><td><input type='text' name='prefixes' value='{$user.prefixes|escape}' /></td></tr>
	<tr><td{if $inv.surname} class='invalid'{/if}>Achternaam:</td><td><input type='text' name='surname' value='{$user.surname|escape}' /></td></tr>
	<tr><td{if $inv.address} class='invalid'{/if}>Straat + huisnummer:</td><td><input type='text' name='address' value='{$user.address|escape}' /></td></tr>
	<tr><td{if $inv.postalcode} class='invalid'{/if}>Postcode:</td><td><input type='text' name='postalcode' maxlength='7' size='7' value='{$user.postalcode|escape}' /></td></tr>
	<tr><td{if $inv.city} class='invalid'{/if}>Woonplaats:</td><td><input type='text' name='city' value='{$user.city|escape}' /></td></tr>
	<tr><td{if $inv.country} class='invalid'{/if}>Land:</td><td><input type='text' name='country' value='{if $user.country}{$user.country|escape}{else}Nederland{/if}' /></td></tr>
	<tr><td{if $inv.dob} class='invalid'{/if}>Geboortedatum:</td><td><input type='text' name='dob1' maxlength='2' size='2' value='{$user.dob1|escape}' />-<input type='text' name='dob2' maxlength='2' size='2' value='{$user.dob2|escape}' />-<input type='text' name='dob3' maxlength='4' size='4' value='{$user.dob3|escape}' /></td></tr>
	<tr><td{if $inv.gender} class='invalid'{/if}>Geslacht:</td><td><input type='radio' name='gender' value='Man'{if $user.gender == 'Man'} checked='checked'{/if}/>Man&nbsp;&nbsp;&nbsp;<input type='radio' name='gender' value='Vrouw'{if $user.gender == 'Vrouw'} checked='checked'{/if}/>Vrouw</td></tr>
	<tr><td{if $inv.emailaddress} class='invalid'{/if}>E-mailadres:</td><td><input type='text' name='emailaddress' value='{$user.emailaddress|escape}' /></td></tr>
</table>

<p>
We willen graag ongeveer weten hoeveel bussen we in moeten zetten. Daarom willen we weten met welk vervoersmiddel je verwacht te komen.<br />
<span{if $inv.transport} class='invalid'{/if}>Ik kom waarschijnlijk met:&nbsp;&nbsp;&nbsp;<input type='radio' name='transport' value='OV'{if $user.transport == 'OV'} checked='checked'{/if}/>het OV&nbsp;&nbsp;&nbsp;<input type='radio' name='transport' value='auto'{if $user.transport == 'auto'} checked='checked'{/if}/>de auto&nbsp;&nbsp;&nbsp;<input type='radio' name='transport' value='unknown'{if $user.gender == 'unknown'} checked='checked'{/if}/>weet ik nog niet</span>
</p>

<p>
<input type='checkbox' name='rules'{if $user.rules} checked='checked'{/if} />{if !$user.rules && $invalid}<span class='invalid'>{/if}Ik ga akkoord met de <a href='info.php?id=12' target='_blank'>huisregels</a>{if !$user.rules && $invalid}</span>{/if}.
</p>

<hr />

<p>
Ik wil graag <select name='amount'>
	<option value='1'>1</option>
	<option value='2'{if $user.amount == 2} selected='selected'{/if}>2</option>
	<option value='3'{if $user.amount == 3} selected='selected'{/if}>3</option>
	<option value='4'{if $user.amount == 4} selected='selected'{/if}>4</option>
	<option value='5'{if $user.amount == 5} selected='selected'{/if}>5</option>
	<option value='6'{if $user.amount == 6} selected='selected'{/if}>6</option>
	<option value='7'{if $user.amount == 7} selected='selected'{/if}>7</option>
	<option value='8'{if $user.amount == 8} selected='selected'{/if}>8</option>
	<option value='9'{if $user.amount == 9} selected='selected'{/if}>9</option>
	</select> kaartje(s) bestellen.
</p>

<p>
<input type='submit' value='Volgende' />
</p>
</form>

{include file='footer.tpl' mode='reg'}