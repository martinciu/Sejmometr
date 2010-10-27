{if $DATA.projekt.id}

  {if $DATA.projekt.response_status ne 200}
    <p class="msg">Response status: <b>{$DATA.projekt.response_status}</b></p>
  {/if}

	{if $DATA.projekty|@count gt 0}
	{assign var="projekty" value=$DATA.projekty}
	<div id="duplikaty_div">
		<table id="duplikaty_table">
		  <tr>
		    <th>Nowy projekt</th>
		    {section name="projekty" loop=$projekty}{assign var="p" value=$projekty[projekty]}
		    <th>Dotychczasowy projekt</th>
		    {/section}
		  </tr>
		  <tr>
		    <td>
		      <a href="/admin/projekty/etapy#id={$DATA.projekt.id}" target="_blank">{$DATA.projekt.id}</a> ({$DATA.projekt.etapy_count})
		    </td>
		    {section name="projekty" loop=$projekty}{assign var="p" value=$projekty[projekty]}
		    <td>
		      <a href="/admin/projekty/etapy#id={$p.id}" target="_blank">{$p.id}</a> ({$p.etapy_count})
		    </td>
		    {/section}
		  </tr>
		  <tr>
		    <td>
		      <a href="http://orka.sejm.gov.pl/proc6.nsf/{$DATA.projekt.sejm_id}?OpenDocument" target="_blank">{$DATA.projekt.sejm_id}</a> ({$DATA.projekt.response_status})
		    </td>
		    {section name="projekty" loop=$projekty}{assign var="p" value=$projekty[projekty]}
		    <td>
		      <a href="http://orka.sejm.gov.pl/proc6.nsf/{$p.sejm_id}?OpenDocument" target="_blank">{$p.sejm_id}</a> ({$p.response_status})
		    </td> 
		    {/section}
		  </tr>
		  <tr class="buttons">
		    <td>{if $DATA.projekt.response_status eq 200}<input type="button" value="Pobierz" projekt_id="{$DATA.projekt.id}" />{/if}</td>
		    {section name="projekty" loop=$projekty}{assign var="p" value=$projekty[projekty]}
		    <td>
		      {if $p.response_status eq 200}<input type="button" value="Pobierz" projekt_id="{$p.id}" />{/if}
		    </td> 
		    {/section}
		  </tr>
		</table>
	</div>
	{/if}
	
	<div class="inner_title_bar">
    Etapy: <b>{$DATA.projekt.etapy_count}</b><span class="separator">·</span><a href="http://orka.sejm.gov.pl/proc6.nsf/{$DATA.projekt.sejm_id}?OpenDocument" target="_blank">Sejm</a><span class="separator">·</span><a href="/admin/projekty/etapy#id=">Etapy</a>
  </div>
	
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