{if $posty|@count gt 0}
<div id="ostatnie_posty">
	<h3>Ostatnie posty:</h3>
	
	{section name="posty" loop=$posty}{assign var="post" value=$posty[posty]}
		{if $post.id ne $glowny_post.id}
			<div class="post">
			  <a href="/blog/{$post.id}" class="tytul">{$post.tytul}</a>
			  <p class="info"><span class="a">{$post.autor}</span>, {$post.data_zapisu}</p>
			  <p class="opis">{$post.opis} <a href="/blog/{$post.id}">więcej &raquo;</a></p>
			</div>
		{/if}
	{/section}
</div>
{/if}