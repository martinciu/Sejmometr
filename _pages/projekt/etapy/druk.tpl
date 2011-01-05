<p class="tytul"><a href="#" onclick="return false;">{$etap.label}</a></p>
{if $etap.dokument_id}
<div class="cc">
  {assign var="_dsize" value="d"}
  {if $dsize}{assign var="_dsize" value=$dsize}{/if}
  {dokument size=$_dsize _params=$etap}
  <div class="ccc">
	  <p>{$etap.title}</p>
    {if !$etap.stanowisko_senatu}
    <p>
	    Autor: {section name="autorzy" loop=$etap.autorzy}{assign var="autor" value=$etap.autorzy[autorzy]}
		  <span class="a">{$autor[1]}</span>{if !$smarty.section.autorzy.last}{if $smarty.section.autorzy.iteration eq $smarty.section.autorzy.total-1} oraz {else}, {/if}{/if}
		{/section}
	  </p>
	  {/if}
  </div>
</div>
{/if}