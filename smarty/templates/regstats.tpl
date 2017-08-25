{include file='header.tpl' title='Registratiestatistieken' mode='admin'}
<table>
		 <tr><td>Onbevestigd:</td><td>{$unconfirmed}</td></tr>
		 <tr><td> </td><td> </td></tr>
		 <tr><td> </td><td> </td></tr>
		 <tr><td>Bevestigd:</td><td>{$confirmed}</td></tr>
		 <tr><td>Betaald laag tarief:</td><td>{$paidlow}</td></tr>
		 <tr><td>Betaald hoog tarief:</td><td>{$paidhigh}</td></tr>
		 <tr><td><b>Totaal:</b></td><td><b>{$total}</b></td></tr>
		 <tr><td> </td><td> </td></tr>
		 <tr><td> </td><td> </td></tr>
		 <tr><td>Man:</td><td>{$male}</td></tr>
		 <tr><td>Vrouw:</td><td>{$female}</td></tr>
		 <tr><td> </td><td> </td></tr>
		 <tr><td> </td><td> </td></tr>
		 <tr><td>Ingecheckt:</td><td>{$checkedin}</td></tr>
</table>
{include file='footer.tpl' mode='admin'}