{include file='header.tpl' title='Event Admin' mode='admin'}
<p>
	<a href='eventadmin.php?mode=new'>Nieuw</a>&nbsp;&nbsp;&nbsp;<a href='eventadmin.php'>Main</a>
</p>
{if $mode == 'edit'}
	<form method='post' action='eventadmin.php'>
	<p>
		<input type='hidden' name='id' value='{$event.id}' />
		<input type='hidden' name='mode' value='submit' />
	</p>
	<table>
		<tr><td>Categorie:</td><td><input type='text' name='category' value='{$event.category|escape}' /></td></tr>
		<tr><td>Paginanaam:</td><td><input type='text' name='name' value='{$event.name|escape}' /></td></tr>
		<tr><td>Max. aant.:</td><td><input type='text' name='maxpart' value='{$event.maxpart}' /></td></tr>
		<tr><td>Max. reserve:</td><td><input type='text' name='maxreserve' value='{$event.maxreserve}' /></td></tr>
	</table>
	<p>
		<textarea name='contents' rows='30' cols='80'>{$event.contents|escape}</textarea>
	</p>
	<p>
		<textarea name='fields' rows='5' cols='80'>{$event.fields|escape}</textarea>
	</p>
	<p>
		<input type='submit' value='Verstuur' />
	</p>
	</form>
	
{else}
	<table>
	{section name='events' loop=$pages}
		<tr><td>{$pages[events].category|escape} - {$pages[events].name|escape}</td><td><a href='eventadmin.php?id={$pages[events].id}&amp;mode=edit'>Wijzig</a></td><td><a href="javascript:deletebox('eventadmin.php?id={$pages[events].id}&amp;mode=delete','{$pages[events].category|escape} - {$pages[events].name|escape}');">Verwijder</td></tr>
	{/section}
	</table>
{/if}
{include file='footer.tpl' mode='admin'}