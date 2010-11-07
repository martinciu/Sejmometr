<div id="wyp_lista">
{section name="lista" loop=$wypowiedzi_lista}{assign var="wyp" value=$wypowiedzi_lista[lista]}
<a class="wyp" href="#{$wyp.id}" name="{$wyp.id}" onclick="return false;">
  <div class="wyp_inner">
    <img src="{if $wyp.avatar eq "1"}/l/3/{$wyp.autor_id}{else}/g/gp_3{/if}.jpg" class="s_avatar" />
    <div class="p_info">
	    <p class="autor"><span class="a">{$wyp.autor}</span></p>
	    <p class="funkcja">{$wyp.funkcja}</p>
    </div>
    <p class="tresc">{$wyp.txt|@strip_tags}</p>
  </div>
</a>
{/section}
</div>