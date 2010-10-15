<div class="_height_controll">
  <p class="tytul">{$DATA.tytul}</p>
  <div class="druk_html"></div>
  <p class="ralign"><a href="http://orka.sejm.gov.pl/Druki6ka.nsf/{$DATA.sejm_id}?OpenDocument" target="_blank">Link do strony sejmowej</a></p>
</div>
<script>
  druk = new Druk({$DATA|@json_encode});
</script>