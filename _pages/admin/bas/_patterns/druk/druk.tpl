<div id="druk" class="_height_controll">
  <div class="druk_title_bar">
	  <p class="tytul">
	    {$DATA.druk.tytul}
	  </p>
  </div>
  
  <div id="druk_form"></div>
</div>
<script>
  _projekty_data = {$DATA.projekty_data|@json_encode};
  druk = new Druk({$DATA.druk|@json_encode});
</script>