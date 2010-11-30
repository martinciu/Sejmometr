{if $debata.typ_id eq 1}
  
  <div class="h1cont">
  {if $debata_typ eq 1}
    <h1><a href="/projekt/{$projekt.id}">{$projekt.tytul}</a></h1>
  {else if $debata_typ eq 2}
    <h1>{$tytul}</h1>
    <p class="info">Ta debata dotyczy {$ilosc_projektow|dopelniacz:'projektu':'projektów':'projektów'}.</p>
  {/if}
  </div>
  
{/if}

<div class="e">
  {$debata.data|sm_kalendarzyk}
  <a href="/debata/{$debata.id}"><img class="ikona_legislacyjna wypowiedzi" src="/g/p.gif"></a>
  
  <div class="c">
    {if $debata.typ_id eq 4}
			<h1><a href="/debata/{$debata.id}">{$debata.tytul}</a></h1>
			<div class="cc">{$debata.opis}</div>
		{else}
      <p class="tytul"><a href="/debata/{$debata.id}">{$label}</a></p>
      <div class="cc">{$debata.opis}</div>
    {/if}
  </div>
</div>