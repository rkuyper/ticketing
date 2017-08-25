{include file='header.tpl' title='Static Admin' mode='admin'}
<p>
	<a href='staticadmin.php?mode=new'>Nieuw</a>&nbsp;&nbsp;&nbsp;<a href='staticadmin.php'>Main</a>
</p>
{if $mode == 'edit'}
	<form method='post' action='staticadmin.php'>
	<p>
		<input type='hidden' name='id' value='{$static.id}' />
		<input type='hidden' name='mode' value='submit' />
	</p>
	<table>
		<tr><td>Categorie:</td><td><input type='text' name='category' value='{$static.category|escape}' /></td></tr>
		<tr><td>Paginanaam:</td><td><input type='text' name='name' value='{$static.name|escape}' /></td></tr>
	</table>
	<p>
		<textarea name='contents' rows='30' cols='80'>{$static.contents|escape}</textarea>
	</p>
	<p>
		<input type='submit' value='Verstuur' />
	</p>
	</form>
	
{else}
	<table>
	{section name='spage' loop=$pages}
		<tr><td>{$pages[spage].category|escape} - {$pages[spage].name|escape}</td><td><a href='staticadmin.php?id={$pages[spage].id}&amp;mode=edit'>Wijzig</a></td><td><a href="javascript:deletebox('staticadmin.php?id={$pages[spage].id}&amp;mode=delete','{$pages[spage].category|escape} - {$pages[spage].name|escape}');">Verwijder</td></tr>
	{/section}
	</table>
{/if}
{include file='footer.tpl' mode='admin'}