<div class="p">
	<div class="autorstatus">
	  <p><span class="label">Autor:</span><span class="a">{$projekt.autor}</span></p>
	  <p><span class="label">Wpłynał:</span>{$projekt.data_wplynal}</p>
	  <p><span class="label">Status:</span>{$projekt.status_slowny}</p>
	  
	</div>
	<p class="opis">{$projekt.opis}</p>
</div>

<div class="_TABS">
{section name="tabs" loop=$_TABS}{assign var="tab" value=$_TABS[tabs]}<a href="{$projekt.id},{$tab[0]}"{if $tab[0] eq $_TAB} class="selected"{/if}>{$tab[1]}</a>{/section}
</div>