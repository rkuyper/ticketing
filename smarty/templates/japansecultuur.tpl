{include file='header.tpl' title='Japanse cultuur'}
<p>
Japanse cultuur gaat verder dan anime, manga en games. Op deze pagina zullen door de Chibicon-staff regelmatig artikelen over de diverse zijden van de Japanse cultuur worden geplaatst. Op ons <a href='http://forum.chibicon.nl/index.php/board,29.0.html'>forum</a> kun je reageren op deze artikelen.
</p>
<br />

{section name='sart' loop=$artikelen}
		<h2><a href='{$artikelen[sart].href}'>{$artikelen[sart].subject}</a></h2>
		<span class='newstime'>{$artikelen[sart].time}</span><br /><br />
		{$artikelen[sart].body}
		<br /><span class='commentslink'><a href='{$artikelen[sart].href}'>Lees verder / reageer</a></span>
{/section}	

{include file='footer.tpl' mode='artikelen'}