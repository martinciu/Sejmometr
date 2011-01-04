<div id="wyp_lista">
{section name="lista" loop=$wypowiedzi_lista}{assign var="wyp" value=$wypowiedzi_lista[lista]}
	<a class="wyp" href="/wypowiedz/{$wyp.id}" wyp_id="{$wyp.id}" wyp_i="{$smarty.section.lista.iteration}" autor_id="{$wyp.autor_id}" avatar="{$wyp.avatar}">
	  <div class="header">
		  <img src="{if $wyp.avatar eq "1"}/l/3/{$wyp.autor_id}{else}/g/gp_3{/if}.jpg" class="avatar d" />
		  <span class="numer">{$smarty.section.lista.iteration}</span>
		  <div class="info">
		    <p class="autor"><span class="a">{$wyp.autor}</span></p>
		    <p class="funkcja"><span class="f">{$wyp.funkcja}</span>{if $wyp.k eq '1' && $wyp.klub} <span class="a">{$wyp.klub}</span>{/if}</p>
		  </div>
	  </div>
	  <p class="tresc">{$wyp.skrot}</p>
	</a>
{/section}
</div>
{literal}
<script>
  $$('#wyp_lista a.wyp').invoke('writeAttribute', {onclick: 'return false;'});
</script>
{/literal}