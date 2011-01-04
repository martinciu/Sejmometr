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
			  
			  <div class="debaty">
			  {assign var="debaty" value=$item.debaty}
			  {section name="debaty" loop=$debaty}
			    {assign var="etap" value=$debaty[debaty]}
			    {assign var="etap" value=$plan.debaty[$etap]}
			    		    
			    <div class="e {$etap.typ}{if $etap.nowa_data} nowa_data{/if}">
				    {$etap.data|kalendarzyk}
				    <a href="/debata/{$etap.id}"><img src="/g/p.gif" class="ikona_legislacyjna wypowiedzi" /></a>
				    <div class="c">
	
				      <p class="tytul"><a href="/debata/{$etap.id}">{$etap.tytul}</a></p>
							<div class="cc">
							  <a href="/debata/{$etap.id}"><img class="debata_baner" src="/resources/debaty/banery/{$etap.id}.jpg" /></a>
							  <p class="opis">{$etap.opis}</p>
							</div>
	
				    </div>
				  </div>
			  {/section}
			  </div>
		
		  </div>
		
		{/section}
	</div>
</div>







<div id="toolbox">
  
  {if $plan.specjalne}
  <div class="tbox">
	  <div class="headerbox">
		  <p class="label">Debaty specjalne:</p>	
		</div>
		
		<div class="debaty specjalne">
		  {assign var="debaty" value=$plan.specjalne}
		  {section name="debaty" loop=$debaty}
		    {assign var="etap" value=$debaty[debaty]}
		    		    
		    <div class="e {$etap.typ}{if $etap.nowa_data} nowa_data{/if}">
			    {$etap.data|kalendarzyk}
			    <a href="/debata/{$etap.id}"><img src="/g/p.gif" class="ikona_legislacyjna wypowiedzi" /></a>
			    <div class="c">
            
			      <p class="tytul"><a href="/debata/{$etap.id}">{$etap.tytul}</a></p>
						<div class="cc">
						  <a href="/debata/{$etap.id}"><img class="debata_baner" src="/resources/debaty/banery/{$etap.id}.jpg" /></a>
						</div>

			    </div>
					<p class="opis">{$etap.opis}</p>

			  </div>
		  {/section}
		</div>
		
  </div>
  {/if}
  
  	
	{if $plan.informacje_biezace}
	<div class="tbox">
	  <div class="headerbox">
		  <p class="label">Informacja bieżąca:</p>	
		</div>
		<div class="debaty">
		  {assign var="debaty" value=$plan.informacje_biezace}
		  {section name="debaty" loop=$debaty}
		    {assign var="etap" value=$debaty[debaty]}
		    		    
		    <div class="e {$etap.typ}{if $etap.nowa_data} nowa_data{/if}">
			    {$etap.data|kalendarzyk}
			    <a href="/debata/{$etap.id}"><img src="/g/p.gif" class="ikona_legislacyjna wypowiedzi" /></a>
			    <div class="c">
            
            <p class="tytul"><a href="/debata/{$etap.id}">{$etap.tytul}</a></p>
						<div class="cc">
						  <p class="opis">{$etap.ilosc_wypowiedzi|dopelniacz:'wypowiedź':'wypowiedzi':'wypowiedzi'}</p>
						</div>

			    </div>
			  </div>
		  {/section}
		</div>
  </div>
  {/if}
  
  
  
  {if $plan.pytania_biezace && 0}
  <div class="tbox">
	  <div class="headerbox">
		  <p class="label">Sprawy bieżące:</p>	
		</div>
		
		<div class="debaty">
		  {assign var="debaty" value=$plan.pytania_biezace}
		  {section name="debaty" loop=$debaty}
		    {assign var="etap" value=$debaty[debaty]}
		    		    
		    <div class="e {$etap.typ}{if $etap.nowa_data} nowa_data{/if}">
			    {$etap.data|kalendarzyk}
			    <a href="/debata/{$etap.id}"><img src="/g/p.gif" class="ikona_legislacyjna wypowiedzi" /></a>
			    <div class="c">
            
            <p class="tytul"><a href="/debata/{$etap.id}">Pytania</a></p>
						<div class="cc">
						  <p class="opis">{$etap.ilosc_wypowiedzi|dopelniacz:'wypowiedź':'wypowiedzi':'wypowiedzi'}</p>
						</div>

			    </div>
			  </div>
		  {/section}
		</div>
	</div>
	{/if}
  
  
  
  {if $plan.oswiadczenia}
  <div class="tbox">
	  <div class="headerbox">
		  <p class="label">Oświadczenia:</p>	
		</div>
		
		<div class="debaty oswiadczenia">
		  
		  {assign var="debaty" value=$plan.oswiadczenia}
		  {section name="debaty" loop=$debaty}
		    {assign var="etap" value=$debaty[debaty]}
		    		    
		    <div class="e {$etap.typ}{if $etap.nowa_data} nowa_data{/if}">
			    {$etap.data|kalendarzyk}
			    <div class="c">

						<div class="cc">
						  <a href="/debata/{$etap.id}"><img class="debata_baner" src="/resources/debaty/banery/{$etap.id}.jpg" /></a>
						  <p class="opis"><a href="/debata/{$etap.id}">{$etap.ilosc_wypowiedzi|dopelniacz:'oświadczenie':'oświadczenia':'oświadczeń'}</a></p>
						</div>

			    </div>
			  </div>
		  {/section}
		</div>
		
	</div>
	{/if}
	
	
</div>