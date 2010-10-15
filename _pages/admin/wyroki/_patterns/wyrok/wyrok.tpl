<div id="druk" class="_height_controll">
  <div class="inner_title_bar">
	  <p class="tytul">
	    {$DATA.wyrok.tytul}
	  </p>
  </div>
  
  <div id="wyrok_form"></div>
</div>
<script>
  wyrok = new Wyrok({$DATA.wyrok|@json_encode});
</script>