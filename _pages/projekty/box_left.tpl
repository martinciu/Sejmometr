{if $miesiac_aktywny}
  <div class="filtr">
    <h2>Data wpłynięcia:</h2>
    <ul>
      <li class="">
	      <a href="#" onclick="return false;"><img src="/g/p.gif" class="mCheckbox superchecked" /></a>
	      <a class="o" href="#" onclick="return false;">{$miesiac|miesiac}</a>
	    </li>
    </ul>
    <p class="filtruj_btn_cont">
	    <a href="{getlink _reset=$filtr_id|cat:",str,miesiac"}" class="_BTN orange">Usuń filtr</a>
	  </p>
  </div>
{/if}

{section name="filtry" loop=$filtry}
	
	{assign var="filtr" value=$filtry[filtry]}
	{assign var="filtr_id" value=$filtr.id}
	{assign var="opcje" value=$filtr.opcje}
	
	<div class="filtr">
	  <h2>{$filtr.tytul}:</h2>
	  
	  <ul>
	    {section name="opcje" loop=$opcje}{assign var="o" value=$opcje[opcje]}
	    <li class="{$o._classes}"{if $o._hide} style="display: none;"{/if}>
	      <a href="#" onclick="return false;"><img o="{$o.id}" src="/g/p.gif" class="mCheckbox" /></a>
	      <a class="o" href="{$o.href}">{$o.tytul}<span class="ilosc">({$o.ilosc})</span></a>
	    </li>
	    {/section}
	  </ul>

	  <p class="filtruj_btn_cont">
	  {if $filtr.aktywny}
	    <a href="{getlink _reset=$filtr_id|cat:",str"}" class="_BTN orange">Usuń filtr</a>
	  {else}
	    <a href="#" onclick="filtruj('{$filtr_id}', this); return false;" class="_BTN orange">Filtruj</a>
	  {/if}
	  </p>
	  
	  {if !$filtr.aktywny && $filtr.opcje|@count gt 10}
	    <p class="wiecejmniej">
	      <a class="wiecej" href="#" onclick="filtr_wiecej(this); return false;">więcej &raquo;</a>
	      <a class="mniej" style="display: none;" href="#" onclick="filtr_mniej(this); return false;">&laquo; mniej</a>
	    </p>
	  {/if}
	    
	</div>
{/section}

{if $smarty.get._TYPE eq "ustawy"}
  <a id="ofensywa" href="/blog/2,W_poszukiwaniu_ofensywy">
    <h3>Ofensywa Legislacyjna Rządu</h3>
    <span>Zobacz szczegóły &raquo;</span>
  </a>
{/if}