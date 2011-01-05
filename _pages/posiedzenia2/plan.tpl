<div id="projekty">
	<div class="headerbox">
	  <p class="label">Rozpatrywane projekty:</p>
	  <div class="filters" style="display: none;">
	    <div class="filter">
	      <p class="label">Status:</p>
	      <select>
	        <option value="0">Wszystkie</option>
	        <option value="1">Tylko przyjęte</option>
	      </select>
	    </div>
	    <div class="filter">
	      <p class="label">Autor:</p>
	      <select>
	        <option value="0">Wszyscy</option>
	        <option>Rada Ministrów</option>
	      </select>
	    </div>
	  </div>
	</div>
	
	<div class="items">
		{assign var="mapa" value=$plan.mapa}
		{section name="mapa" loop=$mapa}{assign var="item" value=$mapa[mapa]}
		  
		  <div class="item">
		  
			  <div class="projekty">
			  {assign var="projekty" value=$item.projekty}
			  {section name="projekty" loop=$projekty}
			    {assign var="p" value=$projekty[projekty]}
			    {assign var="p" value=$plan.projekty[$p]}
			    	    
			    <div class="p">
					  {if $p.dokument_id}<a class="dokument d" href="/projekt/{$p.id}"><img class="d" dokument_id="{$p.dokument_id}" src="/d/3/{$p.dokument_id}.gif" /><span class="druk_nr">druk nr <b>{$p.numer}</b></span></a>{/if}
					  <div class="pc">
					    <p class="tytul"><a href="/projekt/{$p.id}">{$p.tytul}</a></p>
					    <div class="autorstatus">
					      <p><span class="label">Autor:</span> <span class="a">{$p.autor}</span></p>
					    </div>
					    <p class="opis">{$p.opis}</p>
					  </div>
					</div>
			  {/section}
			  </div>
			  

			  {include file="/_pages/projekt/tabs/proces.tpl" proces=$item.proces dsize="e"}
		
		  </div>
		
		{/section}
	</div>
</div>







<div id="toolbox">
  
  <div class="tbox">
	  <div class="headerbox">
		  <p class="label">Pozostałe debaty:</p>	
		</div>
    {include file="pozostale_debaty.tpl"}
	</div>
	 
	 
  <div class="tbox">
	  <div class="headerbox">
		  <p class="label">Oświadczenia:</p>	
		</div>
		
		{assign var="debaty" value=$plan.oswiadczenia}
		{section name="debaty" loop=$debaty}
		  {assign var="etap" value=$debaty[debaty]}
		  		    
		  <div class="idebata osw">
		    {$etap.data|kalendarzyk}
		    <div class="c">
				  <a href="/debata/{$etap.id}"><img class="debata_baner" src="/resources/debaty/banery/{$etap.id}.jpg" /></a>
		    </div>
				<p class="tytul_debaty"><a href="/debata/{$etap.id}">{$etap.ilosc_wypowiedzi|dopelniacz:'oświadczenie':'oświadczenia':'oświadczeń'}</a></p>
		
		  </div>
		{/section}
		
  </div>

</div>