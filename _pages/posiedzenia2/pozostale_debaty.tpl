{assign var="debaty" value=$plan.specjalne}
{section name="debaty" loop=$debaty}
  {assign var="etap" value=$debaty[debaty]}
  		    
  <div class="idebata">
    {$etap.data|kalendarzyk}
    <div class="c">
      
      <p class="tytul"><a href="/debata/{$etap.id}">Debata specjalna</a></p>
			<div class="cc">
			  <a href="/debata/{$etap.id}"><img class="debata_baner" src="/resources/debaty/banery/{$etap.id}.jpg" /></a>
			</div>

    </div>
		<p class="tytul_debaty"><a href="/debata/{$etap.id}">{$etap.tytul}</a></p>

  </div>
{/section}





{assign var="debaty" value=$plan.informacje_biezace}
{section name="debaty" loop=$debaty}
  {assign var="etap" value=$debaty[debaty]}
  		    
  <div class="idebata">
    {$etap.data|kalendarzyk}
    <div class="c">
      
      <p class="tytul"><a href="/debata/{$etap.id}">Informacja bieżąca</a></p>
			<div class="cc">
	      <div class="autorstatus"><p><span class="label">Wnioskodawca:</span> <span class="a">{$etap.autor}</span></p></div>
			  <a href="/debata/{$etap.id}"><img class="debata_baner" src="/resources/debaty/banery/{$etap.id}.jpg" /></a>
			</div>

    </div>
		<p class="tytul_debaty"><a href="/debata/{$etap.id}">{$etap.tytul}</a></p>
  </div>
{/section}







{assign var="debaty" value=$plan.pytania_biezace}
{section name="debaty" loop=$debaty}
  {assign var="etap" value=$debaty[debaty]}
  		    
  <div class="idebata pb">
    {$etap.data|kalendarzyk}
    <div class="c">
      
      <p class="tytul"><a href="/debata/{$etap.id}">Pytania w sprawach bieżących</a></p>
			<div class="cc">
			  <a href="/debata/{$etap.id}"><img class="debata_baner" src="/resources/debaty/banery/{$etap.id}.jpg" /></a>
			</div>

    </div>
		<p class="tytul_debaty"><a href="/debata/{$etap.id}">{$etap.ilosc_wypowiedzi|dopelniacz:'wypowiedź':'wypowiedzi':'wypowiedzi'}</a></p>

  </div>
{/section}