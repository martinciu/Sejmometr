<div class="e{if $debata.typ_id eq 6} oswiadczenia{/if}">
  <a href="/posiedzenia/{$debata.posiedzenie_id}">{$debata.data|sm_kalendarzyk}</a>
  <a href="/debata/{$debata.id}"><img class="ikona_legislacyjna wypowiedzi" src="/g/p.gif"></a>
  
  <div class="c">
    <p class="tytul"><a href="/debata/{$debata.id}">{$debata.typ_tytul}</a></p>
    <div class="cc">{$debata.tytul}</div>
    {if $debata.typ_id eq 3}<p class="wnioskodawca">Wnioskodawca: <span class="a">{$debata.autor}</span></p>{/if}
  </div>
</div>