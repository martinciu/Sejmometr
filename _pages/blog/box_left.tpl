<div id="glowny_post">
	<h2>{$glowny_post.tytul}</h2>
	{if $can_manage}<p class="admin_buttons">
	  <a href="/blog/edytuj/{$glowny_post.id}" class="_BTN orange">Edytuj</a> <a id="btnUsun" href="#" onclick="usun_post({$glowny_post.id}); return false;" class="_BTN orange">Usuń</a>
	</p>{/if}
	<p class="info"><span class="a">{$glowny_post.autor}</span>, {$glowny_post.data_zapisu}</p>
	<div class="opis">{$glowny_post.opis}</div>
	<div class="tresc">{$glowny_post.tresc}</div>
</div>