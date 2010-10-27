<div class="opinie">
  
  {if $opinie.SR|@count gt 0}
  <h2>Stanowisk{if $opinie.SR|@count eq 1}o{else}a{/if} Rady Ministrów:</h2>
	<div class="opinie_box">
	{section name="opinie" loop=$opinie.SR}{assign var="opinia" value=$opinie.SR[opinie]}
	  <div class="data_dokument">
	    {$opinia.data|kalendarzyk}
	    {dokument size='d' _params=$opinia}
	  </div>
	{/section}
	</div>
	{/if}
  
  {if $opinie.BAS|@count gt 0}
  <h2>Opinie Biura Analiz Sejmowych:</h2>
	<div class="opinie_box">
	{section name="opinie" loop=$opinie.BAS}{assign var="opinia" value=$opinie.BAS[opinie]}
	  <div class="data_dokument">
	    {$opinia.data|kalendarzyk}
	    {dokument size='d' _params=$opinia mode="file"}
	  </div>
	{/section}
	</div>
	{/if}
	
	{if $opinie.opinie|@count gt 0}
  {if $opinie.SR|@count gt 0 || $opinie.BAS|@count gt 0}<h2>Pozostałe opinie:</h2>{/if}
	<div class="opinie_box pozostale">
	{section name="opinie" loop=$opinie.opinie}{assign var="opinia" value=$opinie.opinie[opinie]}
	  <div class="data_dokument">
	    {$opinia.data|kalendarzyk}
	    {dokument size='d' _params=$opinia}
	    <p class="autor">{$opinia.autor}</p>
	  </div>
	{/section}
	</div>
	{/if}
	
</div>