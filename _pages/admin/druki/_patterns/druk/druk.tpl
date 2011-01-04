{if $DATA.projekty|@count gt 1}
{assign var="projekty" value=$DATA.projekty}
<div id="duplikaty_div">
	<table id="duplikaty_table">
	  <tr>
	    {section name="projekty" loop=$projekty}{assign var="p" value=$projekty[projekty]}
	    <td>
	      <a href="/admin/projekty/etapy#id={$p.id}" target="_blank">{$p.id}</a> ({$p.etapy_count})
	    </td>
	    {/section}
	  </tr>
	  <tr>
	    {section name="projekty" loop=$projekty}{assign var="p" value=$projekty[projekty]}
	    <td>
	      <a href="http://orka.sejm.gov.pl/proc6.nsf/{$p.sejm_id}?OpenDocument" target="_blank">{$p.sejm_id}</a> ({$p.response_status})
	    </td> 
	    {/section}
	  </tr>
	  <tr class="buttons">
	    {section name="projekty" loop=$projekty}{assign var="p" value=$projekty[projekty]}
	    <td>
	      {if $p.response_status eq 404}<input type="button" value="Usuń" projekt_id="{$p.id}" />{/if}
	    </td> 
	    {/section}
	  </tr>
	</table>
</div>
{/if}

<div id="druk" class="_height_controll">
  <div class="druk_title_bar">
	  <p class="tytul">{$DATA.druk.tytul_oryginalny}</p>
  </div>
  
  {if $DATA.druki_oryginalne|@count gt 0}
    <div class="druki_oryginalne">
      Ten druk posiada dokument, który jest wspólny dla druków: 
      {section name="druki" loop=$DATA.druki_oryginalne}{assign var="d" value=$DATA.druki_oryginalne[druki]}
        <a href="/admin/druki#id={$d}">druk</a> 
      {/section}
    </div>
  {/if}
  
  <div id="druk_form"></div>
</div>
<script>
  _druki_data = {$DATA.druki_data|@json_encode};
  _projekty_data = {$DATA.projekty_data|@json_encode};
  druk = new Druk({$DATA.druk|@json_encode});
</script>