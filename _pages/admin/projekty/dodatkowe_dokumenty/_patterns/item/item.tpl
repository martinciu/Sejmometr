<div id="item" class="_height_controll">
  <div class="inner_title_bar">
	  <a href="/projekt/{$DATA.item.projekt_id}" target="_blank">Projekt</a><span class="separator">·</span><a href="/admin/druki#id={$DATA.item.druk_id}" target="_blank">Druk</a>
  </div>
  
  <div id="item_form"></div>
</div>
<script>
  item = new Item({$DATA.item|@json_encode});
</script>