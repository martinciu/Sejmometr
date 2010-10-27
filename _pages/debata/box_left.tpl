<div id="wyp_lista">
{section name="lista" loop=$wypowiedzi_lista}{assign var="wyp" value=$wypowiedzi_lista[lista]}
<a class="wyp" href="#{$wyp.id}" name="{$wyp.id}" onclick="return false;">
  <div class="wyp_inner">
    <p class="tresc">{$wyp.txt|@strip_tags}</p>
  </div>
</a>
{/section}
</div>