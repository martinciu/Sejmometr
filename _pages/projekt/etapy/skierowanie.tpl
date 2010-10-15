<p class="tytul">Skierowanie</p>
<div class="cc">
	<p>
	{if $etap.FORUM_SEJMU}Do czytania na posiedzeniu Sejmu{else}
	Do 
	{section name="adresaci" loop=$etap.adresaci}{assign var="adresat" value=$etap.adresaci[adresaci]}
	  <span class="a">{$adresat[1]}</span>{if !$smarty.section.adresaci.last}{if $smarty.section.adresaci.iteration eq $smarty.section.adresaci.total-1} oraz {else}, {/if}{/if}
	{/section}
	{/if}
	</p>
	{if $etap.data_zalecenie ne "0000-00-00"}<p>Zalecono przedstawienie sprawozdania do {$etap.data_zalecenie}</p>{/if}
</div>