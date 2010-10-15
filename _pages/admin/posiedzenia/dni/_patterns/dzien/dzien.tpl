<div id="dzien" class="_height_controll">
  
  <div id="tools">
    <p><a href="http://orka2.sejm.gov.pl/Debata6.nsf/{$DATA.dzien.sejm_id}?OpenDocument" target="_blank">Strona sejmowa</a></p>
	  <p>Posiedzenie nr: {$DATA.dzien.posiedzenie_numer}</p>
	  <p>Status: {$DATA.dzien.status}</p>
	  <p>Analiza wystąpień: {$DATA.dzien.analiza_wystapienia}</p>
	  <p><a class="btnSprawdzStatusyWypowiedzi" href="#" onclick="return false;">Sprawdź statusy wypowiedzi</a></p>
	  <p><a class="btnSprawdzStatusyGlosowan" href="#" onclick="return false;">Sprawdź statusy głosowań</a></p>
  </div>
  
  <table id="punkty_table"><tr>
	  <td>
	  {section name="punkty_wypowiedzi" loop=$DATA.punkty_wypowiedzi}{assign var="punkt" value=$DATA.punkty_wypowiedzi[punkty_wypowiedzi]}
	    <a href="/admin/posiedzenia/punkty_wypowiedzi#id={$punkt.id}">{$punkt.sejm_id}</a>
	  {/section}
	  </td><td>
	  {section name="punkty_glosowania" loop=$DATA.punkty_glosowania}{assign var="punkt" value=$DATA.punkty_glosowania[punkty_glosowania]}
	    <a href="/admin/posiedzenia/punkty_glosowania#id={$punkt.id}">{$punkt.sejm_id}</a>
	  {/section}
	  </td>
	</tr></table>

</div>
<script>
  dzien = new Dzien({$DATA.dzien|@json_encode});
</script>