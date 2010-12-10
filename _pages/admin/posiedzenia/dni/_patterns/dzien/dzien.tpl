<div id="dzien" class="_height_controll">
  
  <div class="inner_title_bar" id="tools">
    <a href="http://orka2.sejm.gov.pl/Debata6.nsf/{$DATA.dzien.sejm_id}?OpenDocument" target="_blank">Strona&nbsp;sejmowa</a> <span class="separator">·</span> <a href="/admin/dzien_porownywarka?dzien_id={$DATA.dzien.id}">Porównywarka</a>
    <br/>
    Posiedzenie&nbsp;id:&nbsp;<b>{$DATA.dzien.posiedzenie_id}</b> <span class="separator">·</span> Posiedzenie&nbsp;nr:&nbsp;<b>{$DATA.dzien.posiedzenie_numer}</b> <span class="separator">·</span> Status:&nbsp;<b>{$DATA.dzien.status}</b>
    <br/>
    Data&nbsp;pobrania&nbsp;modelu:&nbsp;<b>{$DATA.dzien.data_pobrania_modelu}</b>
    <br/>
    Data&nbsp;sprawdzenia&nbsp;posiedzenia:&nbsp;<b>{$DATA.dzien.data_sprawdzenia}</b>
  </div>
  
  
  <div id="duplikaty_div">
		<table id="duplikaty_table">
		  <tr>
		    <th>Wypowiedzi</th>
		    <th>Głosowania</th>
		  </tr>
		  <tr>
		    <td>{$DATA.dzien.analiza_wystapienia} - {$DATA.dzien.analiza_wystapienia_slownie}</td>
		    <td>{$DATA.dzien.analiza_glosowania} - {$DATA.dzien.analiza_glosowania_slownie}</td>
		  </tr>
		  <tr>
		    <td>{if $DATA.statusy_wypowiedzi|@count eq 0}<i>Brak wypowiedzi</i>{else}{section name="statusy" loop=$DATA.statusy_wypowiedzi}<p>{$DATA.statusy_wypowiedzi[statusy][0]} - {$DATA.statusy_wypowiedzi[statusy][1]}</p>{/section}{/if}</td>
		    <td>{if $DATA.statusy_glosowania|@count eq 0}<i>Brak głosowań</i>{else}{section name="statusy" loop=$DATA.statusy_glosowania}<p>{$DATA.statusy_glosowania[statusy][0]} - {$DATA.statusy_glosowania[statusy][1]}</p>{/section}{/if}</td>
		  </tr>
		  <tr class="punkty_row">
			  <td>
				  {if $DATA.punkty_wypowiedzi|@count eq 0}<i>Brak punktów wypowiedzi</i>{/if}
				  {section name="punkty_wypowiedzi" loop=$DATA.punkty_wypowiedzi}{assign var="punkt" value=$DATA.punkty_wypowiedzi[punkty_wypowiedzi]}
				    <a href="/admin/posiedzenia/punkty_wypowiedzi#id={$punkt.id}">{$punkt.sejm_id}</a>
				  {/section}
			  </td><td>
				  {if $DATA.punkty_glosowania|@count eq 0}<i>Brak punktów głosowań</i>{/if}
				  {section name="punkty_glosowania" loop=$DATA.punkty_glosowania}{assign var="punkt" value=$DATA.punkty_glosowania[punkty_glosowania]}
				    <a href="/admin/posiedzenia/punkty_glosowania#id={$punkt.id}">{$punkt.sejm_id}</a>
				  {/section}
			  </td>		  
		  </tr>{if $DATA.punkty_wypowiedzi|@count eq 0 && $DATA.punkty_glosowania|@count eq 0}
		  <tr class="buttons">
		    <td colspan="2">
		      <p><input id="usun_btn" type="button"value="Usuń ten dzień i sprawdź posiedzenie" /></p>
		      <p><input id="analizuj_btn" type="button"value="Analizuj ten dzień ponownie" /></p>
		    </td>
		  </tr>{/if}
		</table>
	</div>
 

</div>
<script>
  dzien = new Dzien({$DATA.dzien|@json_encode}, {$DATA.wypowiedzi|@json_encode});
</script>