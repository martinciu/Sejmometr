<div class="_BOX">
	<h1 class="_BOX_HEADER">
	  <a href="/informacje_biezace">Informacje bieżące</a>
	  <span class="p">to debaty w sprawie informacji udzielanych w Sejmie przez przedstawicieli Rady Ministrów. O udzielenie informacji może wnioskować klub poselski lub grupa 15 posłów.</span>
	  
	  <p class="total">{$pag.elementy_start}-{$pag.elementy_koniec} z {$pag.total}</span>
	</h1>
	<p class="_SHADOW _WHITE_TOP">&nbsp;</p>
	<div class="_INNER">
	
		{include file="box.tpl"}
	
	</div>
	<p class="_SHADOW _WHITE_BOTTOM">&nbsp;</p>
	<div class="_BOX_FOOTER">
	<p class="_PAGINATION">
	  {if $pag.ilosc_stron gt 1}
	  {if $pag.strony_start gt 1}<a class="first" href="{getlink str=1}">1</a><span class="separator first">·</span>{/if}{section name="paginacja" start=$pag.strony_start loop=$pag.strony_koniec step=1}{assign var="str" value=$smarty.section.paginacja.index}<a {if $str eq $pag.strona}class="selected" {/if}href="{getlink str=$str}">{$str}</a>{/section}{if $pag.strony_koniec lte $pag.ilosc_stron}<span class="separator last">·</span><a class="last" href="{getlink str=$pag.ilosc_stron}">{$pag.ilosc_stron}</a>{/if}
	  {else}&nbsp;{/if}
	</p>
	<div>
</div>