<p class="_PAGINATION">
  {if $paginacja.ilosc_stron gt 1}
  {if $paginacja.strony_start gt 1}<a class="first" href="{getlink str=1}">1</a><span class="separator first">·</span>{/if}{section name="paginacja" start=$paginacja.strony_start loop=$paginacja.strony_koniec step=1}{assign var="str" value=$smarty.section.paginacja.index}<a {if $str eq $paginacja.strona}class="selected" {/if}href="{getlink str=$str}">{$str}</a>{/section}{if $paginacja.strony_koniec lte $paginacja.ilosc_stron}<span class="separator last">·</span><a class="last" href="{getlink str=$paginacja.ilosc_stron}">{$paginacja.ilosc_stron}</a>{/if}
  {else}&nbsp;{/if}
</p>