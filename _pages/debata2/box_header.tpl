{$debata.data|sm_kalendarzyk}
<img class="ikona_legislacyjna" alt="Debata" src="/g/p.gif">
{if $debata_typ eq 1}
<p class="tytul"><a href="/debata/{$debata.id}">{$projekt.czytanie_label}</a></p>
<div id="projekty">
  <div class="projekt">
    {if $projekt.dokument_id}<a class="dokument e" href="/projekt/{$projekt.id}"><img class="d" dokument_id="{$projekt.dokument_id}" src="/d/4/{$projekt.dokument_id}.gif" /></a>{/if}
    <div class="c">
      <p class="t"><a href="/projekt/{$projekt.id}">{$projekt.tytul}</a></p>
      <p>Druk numer: <b>{$projekt.numer}</b>, autor: <span class="a">{$projekt.autor}</b></span></p>
    </div>
  </div>
</div>
{/if}