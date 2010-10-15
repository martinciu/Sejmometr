{docthumbs id=$DATA.id size="2" lfloat=true}

<ul class="flags">
  <li>{$DATA.pobrano|pobrano_status}</li>
  <li>{$DATA.obraz|obraz_status}</li>
  <li>{$DATA.scribd|scribd_status}</li>
</ul>


<a href="#" onclick="this.up('.item').down('.tools').toggle(); return false;">Narzędzia</a>

<div class="tools" style="display: none;">
  <a target="_blank" href="{$DATA.download_url}">Link do dokumentu źródłowego</a>
  <a class="btn pobierz_plik" href="#" onclick="return false;">Spróbuj pobrać plik</a>
  <a class="btn automator" href="#" onclick="return false;">Wrzuć ponownie do Automatora</a>
  <a class="btn pobierz_automator" href="#" onclick="return false;">Pobierz z Automatora</a>
  <a target="_blank" href="http://www.scribd.com/doc/{$DATA.scribd_doc_id}">Link do strony Scribd</a>
  <a class="btn sprawdz_status_scribd" href="#" onclick="return false;">Sprawdź status Scribd</a>
  <a class="btn ustaw_reczny_scribd" href="#" onclick="return false;">Ustaw ScribdId ręcznie</a>
  <a class="btn usun_scribd" href="#" onclick="return false;">Usuń ze Scribd i wrzuć do Automatora</a>
</div>

<script>
  mBrowser.item_standard_title.update('{$DATA.plik}');
  mBrowser.arm();
</script>