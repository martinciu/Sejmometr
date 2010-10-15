<div class="projekty">
{section name="projekty" loop=$projekty}{assign var="p" value=$projekty[projekty]}
<div class="p">
  {if $p.dokument_id}<a class="dokument d" href="/projekt/{$p.id}"><img class="d" dokument_id="{$p.dokument_id}" src="/d/3/{$p.dokument_id}.gif" /></a>{/if}
  <div class="pc">
    <p class="tytul"><a href="/projekt/{$p.id}">{$p.tytul}</a></p>
    <div class="autorstatus">
      <p><span class="label">Autor:</span><span class="a">{$p.autor}</span></p>
      <p><span class="label">Wpłynął:</span>{$p.data_wplynal}</p>
      <p><span class="label">Status:</span>{$p.status_slowny}</p>
    </div>
    <p class="opis">{$p.opis}</p>
  </div>
</div>
{/section}
</div>