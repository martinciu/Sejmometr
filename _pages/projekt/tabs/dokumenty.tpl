<div class="opinie">
  
  {if $dokumenty.akty_wykonawcze|@count gt 0}
  <h2>Projekty aktów wykonawczych:</h2>
	<div class="opinie_box">
	{section name="opinie" loop=$dokumenty.akty_wykonawcze}{assign var="opinia" value=$dokumenty.akty_wykonawcze[opinie]}
	  <div class="data_dokument">
	    {$opinia.data|kalendarzyk}
	    {dokument size='d' _params=$opinia}
	  </div>
	{/section}
	</div>
	{/if}
	
	{if $dokumenty.dokumenty|@count gt 0}
  <h2>Dokumenty:</h2>
	<div class="opinie_box pozostale">
	{section name="opinie" loop=$dokumenty.dokumenty}{assign var="opinia" value=$dokumenty.dokumenty[opinie]}
	  <div class="data_dokument">
	    {$opinia.data|kalendarzyk}
	    {dokument size='d' _params=$opinia}
	    <p class="autor">{$opinia.tytul}</p>
	  </div>
	{/section}
	</div>
	{/if}
  
  {if $dokumenty.SR|@count gt 0}
  <h2>Stanowisk{if $dokumenty.SR|@count eq 1}o{else}a{/if} Rady Ministrów:</h2>
	<div class="opinie_box">
	{section name="opinie" loop=$dokumenty.SR}{assign var="opinia" value=$dokumenty.SR[opinie]}
	  <div class="data_dokument">
	    {$opinia.data|kalendarzyk}
	    {dokument size='d' _params=$opinia}
	  </div>
	{/section}
	</div>
	{/if}
  
  {if $dokumenty.BAS|@count gt 0}
  <h2>Opinie Biura Analiz Sejmowych:</h2>
	<div class="opinie_box">
	{section name="opinie" loop=$dokumenty.BAS}{assign var="opinia" value=$dokumenty.BAS[opinie]}
	  <div class="data_dokument">
	    {$opinia.data|kalendarzyk}
	    {dokument size='d' _params=$opinia mode="file"}
	  </div>
	{/section}
	</div>
	{/if}
	
	{if $dokumenty.opinie|@count gt 0}
  <h2>{if $dokumenty.SR|@count gt 0 || $dokumenty.BAS|@count gt 0}Pozostałe opinie{else}Opinie{/if}:</h2>
	<div class="opinie_box pozostale">
	{section name="opinie" loop=$dokumenty.opinie}{assign var="opinia" value=$dokumenty.opinie[opinie]}
	  <div class="data_dokument">
	    {$opinia.data|kalendarzyk}
	    {dokument size='d' _params=$opinia}
	    <p class="autor">{$opinia.autor}</p>
	  </div>
	{/section}
	</div>
	{/if}
	
</div>