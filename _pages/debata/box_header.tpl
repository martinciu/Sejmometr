{$debata.data|sm_kalendarzyk}
<img class="ikona_legislacyjna" alt="Debata" src="/g/p.gif">
<p class="tytul"><a href="/debata/{$debata.id}">{$label}</a></p>
<div id="projekty">
	{if $debata.typ_id eq 1}
	  
	  {if $debata_typ eq 1}
	  <div class="projekt">
	    {if $projekt.dokument_id}<a class="dokument e" href="/projekt/{$projekt.id}"><img class="d" dokument_id="{$projekt.dokument_id}" src="/d/4/{$projekt.dokument_id}.gif" /></a>{/if}
	    <div class="c">
	      <p class="t"><a href="/projekt/{$projekt.id}">{$projekt.tytul}</a></p>
	      <p>Druk numer: <b>{$projekt.numer}</b>, autor: <span class="a">{$projekt.autor}</b></span></p>
	    </div>
	  </div>
	  {else if $debata_typ eq 2}
	    <p class="t">{$tytul}</p>
	    <p>Ta debata dotyczy {$ilosc_projektow|dopelniacz:'projektu':'projektów':'projektów'}.</p>
	  {/if}
	  
	{else if $debata.typ_id eq 4}
	
		<p class="t"><a href="/debata/{$debata.id}">{$debata.tytul}</a></p>
	
	{/if}
</div>
