{section name="model" loop=$model}{assign var="item" value=$model[model]}
<div class="i" typ="{$item.typ}" autor="{$item.autor}">
  {if $item.typ eq 1}
    <b>{$item.autor}</b>
  {else}
    {$item.text}
  {/if}
</div>
{/section}