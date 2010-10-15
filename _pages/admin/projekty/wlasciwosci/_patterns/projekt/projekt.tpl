{if $DATA.projekt.id}
	{if $DATA.projekty|@count gt 0}
	{assign var="projekty" value=$DATA.projekty}
	<table id="duplikaty_table">
	  <tr>
	    <th>Nowy projekt</th>
	    {section name="projekty" loop=$projekty}{assign var="p" value=$projekty[projekty]}
	    <th>Dotychczasowy projekt</th>
	    {/section}
	  </tr>
	  <tr>
	    <td>
	      <a href="/admin/projekty/etapy#id={$DATA.projekt.id}" target="_blank">{$DATA.projekt.id}</a>
	    </td>
	    {section name="projekty" loop=$projekty}{assign var="p" value=$projekty[projekty]}
	    <td><a href="/admin/projekty/etapy#id={$p.id}" target="_blank">{$p.id}</a></td>
	    {/section}
	  </tr>
	  <tr>
	    <td>
	      <a href="http://orka.sejm.gov.pl/proc6.nsf/{$DATA.projekt.sejm_id}?OpenDocument" target="_blank">{$DATA.projekt.sejm_id}</a>
	    </td>
	    {section name="projekty" loop=$projekty}{assign var="p" value=$projekty[projekty]}
	    <td><a href="http://orka.sejm.gov.pl/proc6.nsf/{$p.sejm_id}?OpenDocument" target="_blank">{$p.sejm_id}</a></td>
	    {/section}
	  </tr>
	</table>
	<input type="button" id="btn_swap_ids" value="Zamień id" />
	
	{/if}
	
	<div id="projekt" class="_height_controll">
	  <div id="projekt_form"></div>
	</div>
	<script>
	  var _druki_data = {$DATA.druki_data|@json_encode};
	  projekt = new Projekt({$DATA.projekt|@json_encode});
	</script>
{else}
  Dokument skasowany
{/if}