<div id="projekt" class="_height_controll">
  <div class="inner_title_bar">
    <p><span id="typ_span"></span>, autor: <a id="autor_a" href="#" onclick="return false;"></a>{if $DATA.projekt.html_zmiana eq '1'}, zmiana html{/if}</p>
    <p class="buttons"><a target="_blank" href="http://orka.sejm.gov.pl/proc6.nsf/{$DATA.projekt.sejm_id}?OpenDocument">Sejm</a> · <a href="/admin/projekty/wlasciwosci#id={$DATA.projekt.id}">Właściwości</a></p>
    <p id="status_slowny" class="tytul">{$DATA.projekt.status_slowny}</p>
  </div>
  <ul id="etapy" class="_height_controll"></ul>
</div>
<script>
  projekt = new Projekt({$DATA.projekt|@json_encode}, {$DATA.druki|@json_encode}, {$DATA.punkty_wypowiedzi|@json_encode}, {$DATA.punkty_glosowania|@json_encode}, {$DATA.isap|@json_encode}, {$DATA.wyroki|@json_encode}, {$DATA.etapy|@json_encode});
</script>