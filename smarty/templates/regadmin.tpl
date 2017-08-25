{include file='header.tpl' title='Registratie Admin' mode='admin'}
<div id='leftstrip'>
	<form action='regadmin.php' method='post'>
	<p>
    	<input type='hidden' name='mode' value='search' />
    </p>
    
    <table>
    	<tr><td>Regnr.:</td><td><input type='text' maxlength='8' size='8' name='ticketnumber' /></td></tr>
    	<tr><td>Reg.datum:</td><td><input type='text' name='regdate' maxlength='19' size='19' /></td></tr>
    	<tr><td>Status:</td><td><select name='state'>
  			<option value=''> </option>
    		<option value='Onbevestigd'>Onbevestigd</option>
    		<option value='Bevestigd'>Bevestigd</option>
    		<option value='Betaald'>Betaald</option>
   			<option value='Con-ticket'>Con-ticket</option>
    		<option value='Ingecheckt'>Ingecheckt</option>
    		<option value='Gewaarschuwd'>Gewaarschuwd</option>
    		<option value='Inactief'>Inactief</option>
    		</select></td></tr>
  		<tr><td>Voornaam:</td><td><input type='text' name='firstname' /></td></tr>
  		<tr><td>Tussenv.:</td><td><input type='text' name='prefixes' /></td></tr>
  		<tr><td>Achternaam:</td><td><input type='text' name='surname' /></td></tr>
  		<tr><td>Adres:</td><td><input type='text' name='address' /></td></tr>
  		<tr><td>Postcode:</td><td><input type='text' name='postalcode' maxlength='7' size='7' /></td></tr>
  		<tr><td>Woonplaats:</td><td><input type='text' name='city' /></td></tr>
  		<tr><td>Land:</td><td><input type='text' name='country' /></td></tr>  
  		<tr><td>Geboortedatum:</td><td><input type='text' name='dob' maxlength='10' size='10' /></td></tr>
 		<tr><td>Geslacht:</td><td><input type='radio' name='gender' value='Man' />Man&nbsp;&nbsp;&nbsp;<input type='radio' name='gender' value='Vrouw'/>Vrouw</td></tr>
 		<tr><td>E-mailadres:</td><td><input type='text' name='emailaddress' /></td></tr>
  		<tr><td><input type='submit' value='Zoek' /></td><td> </td></tr>
  	</table>
  	</form>
</div>

{if $mode=='search'}
	<div id='rightstrip'>
	
	{if !$results}
		<p>
			Geen resultaten gevonden.
		</p>
	{/if}
	
	{section name='regs' loop=$results}
		<form action='regadmin.php' method='post'>
		<p>
    		<input type='hidden' name='mode' value='edited' />
    		<input type='hidden' name='ticketnumber' value='{$results[regs].ticketnumber}' />
    		<input type='hidden' name='id' value='{$results[regs].id}' />
    	</p>
    	<table>
    		<tr><td>Regnr.:</td><td>{$results[regs].ticketnumber}-{$results[regs].id} <a href='./regadmin.php?mode=genmails&amp;ticketnumber={$results[regs].ticketnumber}'>Toon mails</a></td></tr>
    		{if $results[regs].id == '1'}
    			<tr><td>Aant. tickets:</td><td>{$results[regs].aantaltickets}</td></tr>
    			<tr><td>Reg.datum:</td><td>{$results[regs].regdate}</td></tr>
    			{if $results[regs].state=='Betaald'}
    				<tr><td>Bet.datum:</td><td>{$results[regs].paydate}</td></tr>
    			{/if}
	   			<tr><td>Status:</td><td>{$results[regs].state}
	   			{if $results[regs].state=='Bevestigd' || $results[regs].state=='Gewaarschuwd' || $results[regs].state=='Inactief'}
    				<a href="javascript:paydatebox({$results[regs].ticketnumber});">Betaal €{$results[regs].totaalprijs}</a>
    			{/if}
    			</td></tr>
	   		{/if}
	   		
  			<tr><td>Voornaam:</td><td><input type='text' name='firstname' value='{$results[regs].firstname|escape}' /></td></tr>
  			<tr><td>Tussenv.:</td><td><input type='text' name='prefixes' value='{$results[regs].prefixes|escape}' /></td></tr>
  			<tr><td>Achternaam:</td><td><input type='text' name='surname' value='{$results[regs].surname|escape}' /></td></tr>
  			
  			{if $results[regs].id == '1'}
  				<tr><td>Adres:</td><td><input type='text' name='address' value='{$results[regs].address|escape}' /></td></tr>
  				<tr><td>Postcode:</td><td><input type='text' name='postalcode' value='{$results[regs].postalcode|escape}' maxlength='7' size='7' /></td></tr>
  				<tr><td>Woonplaats:</td><td><input type='text' name='city' value='{$results[regs].city|escape}' /></td></tr>
  				<tr><td>Land:</td><td><input type='text' name='country' value='{$results[regs].country|escape}' /></td></tr>  
  			{/if}
  			
  			<tr><td>Geboortedatum:</td><td><input type='text' name='dob' value='{$results[regs].dob|escape}' maxlength='10' size='10' /></td></tr>
 			<tr><td>Geslacht:</td><td><input type='radio' name='gender' value='Man'{if $results[regs].gender == 'Man'} checked='checked'{/if} />Man&nbsp;&nbsp;&nbsp;<input type='radio' name='gender' value='Vrouw'{if $results[regs].gender == 'Vrouw'} checked='checked'{/if} />Vrouw</td></tr>
 			
 			{if $results[regs].id == '1'}
  				<tr><td>E-mailadres:</td><td><input type='text' name='emailaddress' value='{$results[regs].emailaddress|escape}' /></td></tr>
  			{/if}
 			
 			<tr><td><input type='submit' value='Wijzig' /></td><td><a href="javascript:deletebox('regadmin.php?mode=delete&amp;ticketnumber={$results[regs].ticketnumber}&amp;id={$results[regs].id}','{$results[regs].ticketnumber}-{$results[regs].id}');">Verwijder</a></td></tr>
  		</table>
  		</form>
  	{/section}
  	</div>
{/if}

{include file='footer.tpl' mode='admin'}