<p class="tytul"><a href="#" onclick="return false;">{$etap.label}</a></p>
{if $etap.dokument_id}
<div class="cc">
  {dokument size='d' _params=$etap}
  <div class="ccc">
    <p>
	    Autor: {section name="autorzy" loop=$etap.autorzy}{assign var="autor" value=$etap.autorzy[autorzy]}
		  <span class="a">{$autor[1]}</span>{if !$smarty.section.autorzy.last}{if $smarty.section.autorzy.iteration eq $smarty.section.autorzy.total-1} oraz {else}, {/if}{/if}
		{/section}
	  </p>
	  <p>{$etap.title}</p>
  </div>
</div>
{/if}