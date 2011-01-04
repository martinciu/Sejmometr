<div class="_BOX">
	<h1 class="_BOX_HEADER">
		<div class="e{if $debata.typ_id eq 6} oswiadczenia{/if}">
		  <a href="/posiedzenia/{$debata.posiedzenie_id}">{$debata.data|sm_kalendarzyk}</a>
		  <a href="/debata/{$debata.id}"><img class="ikona_legislacyjna wypowiedzi" src="/g/p.gif"></a>
		  
		  <div class="c">
		    <p class="tytul"><a href="/debata/{$debata.id}">{$debata.typ_tytul}</a></p>
		    <div class="cc">{$debata.tytul}</div>
        {if $debata.typ_id eq 3}<p class="wnioskodawca">Wnioskodawca: <span class="a">{$debata.autor}</span></p>{/if}

		  </div>
		</div>
	</h1>
	<p class="_SHADOW _WHITE_TOP">&nbsp;</p>
	<div class="_INNER">
	
		<div id="wyp">
		  <div class="info">
  		  <img src="{if $wyp.avatar eq "1"}/l/2/{$wyp.autor_id}{else}/g/gp_2{/if}.jpg" class="avatar c" />
		    <div class="opis">
		      <p class="autor"><span class="a">{$wyp.autor}</span></p>
		      <p class="funkcja"><span class="f">{$wyp.funkcja}</span>{if $wyp.k eq '1' && $wyp.klub} <span class="a">{$wyp.klub}</span>{/if}</p>
		    </div>
		    <div class="link"><a href="/debata/{$debata.id}#{$wyp.id}">Zobacz tę wypowiedź w kontekście debaty &raquo;</a></div>
		  </div>
		  <div class="text">{$wyp.text}</div>
		</div>
	
	</div>
	<p class="_SHADOW _WHITE_BOTTOM">&nbsp;</p>
	<p class="_BOX_FOOTER">&nbsp;</p>
</div>