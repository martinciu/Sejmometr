<div id="druk" class="_height_controll">
  <div class="druk_title_bar">
	  <p class="tytul"></p>
  </div>
  
  <div id="druk_form"></div>
</div>
<script>
  _druki_data = {$DATA.druki_data|@json_encode};
  _projekty_data = {$DATA.projekty_data|@json_encode};
  druk = new Druk({$DATA.druk|@json_encode});
</script>