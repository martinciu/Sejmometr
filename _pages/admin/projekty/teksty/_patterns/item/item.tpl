<div id="item">
  <div class="inner_title_bar">
	  <a href="/dokumenty/{$DATA.item.dokument_id}.pdf" target="_blank">PDF ({$DATA.item.pdf_size} MB)</a>
  </div>
  
  <div id="tabs" class="mTabs">
    <p class="header"></p>
    <div class="content _height_controll"></div>
  </div>
  
</div>
<script>
  item = new Item({$DATA.item|@json_encode});
</script>