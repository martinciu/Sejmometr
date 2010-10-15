{if $DATA.error eq "deleted"}
  Dokument skasowany  
{else}{if $DATA.error eq 1}

  Error 1
  {$DATA.dokumenty_stare|@var_export}
  
{else}
	<table id="readers"><tr>
	  <td>
	   	    
	    <h2 class="dok_title">{$DATA.dokument_A.typ}</h2>
	    
	      
	      
	      {if $DATA.typ eq "bas"}
		      
		      {if $DATA.bas_A}
			      <h4>Tytuł dokumentu:</h4>
			      <p>{$DATA.bas_A.tytul}</p>
			      
			      <h4>Dokument id:</h4>
			      <p>{$DATA.bas_A.dokument_id}</p>
			      
			      <h4>Projekty:</h4>
			      {section name="projekty" loop=$DATA.projekty_A}{assign var="projekt" value=$DATA.projekty_A[projekty]}
			        <div class="projekt">
			          <p><b>{$projekt.numer}</b> {$projekt.tytul} <a href="http://orka.sejm.gov.pl/proc6.nsf/{$projekt.sejm_id}?OpenDocument" target="_blank">[sejm]</a></p>
			        </div>
			      {/section}
			      
			      <div class="dok_buttons">
			        <input id="bas_zapisz_btn" onclick="bas_zapisz(); return false;" type="button" value="Zmień nazwę pliku i zapisz" />
			      </div>
		      {else}
		        Nie ma odpowiedniego wpisu w tabeli BAS
		        
		        <br/><br/>
		        <p>dok_gid:</p>
		        {$DATA.dokument_A.gid|@var_export}
		        
		        <br/><br/>
		        <p>projekty_ids:</p>
		        {$DATA.projekty_ids|@var_export}
		        
		        <br/><br/>
		        <p>bas_ids:</p>
		        {$DATA.bas_ids|@var_export}
		        
		        <div class="dok_buttons">
			        <input id="bas_skasuj_pobierz_projekt_btn" onclick="bas_skasuj_pobierz_projekt(); return false;" type="button" value="Skasuj dokument i pobierz jeszcze raz projekt" />
			      </div>
		      {/if}
		      
	      {/if}
	      
	     
	    <div id="tools" class="_height_controll">
	      <a href="{$DATA.dokument_A.download_url}" target="_blank">Link do dokumentu w systemie Sejmu</a>
	    </div>
	    
	  </td><td>
	    <div id="scribd_reader" class="reader _height_controll"></div>
	  </td>
	</tr></table>
	
	<script>
	  dokument_id = {$DATA.id};
	  mBrowser.itemTitleUpdate('{$DATA.dokument_A.plik}');
	  init_scribd_reader('{$DATA.dokument_B.scribd_doc_id}', '{$DATA.dokument_B.scribd_access_key}');
	</script>
	{/if}
	
{/if}