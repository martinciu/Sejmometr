<div id="item" class="_height_controll">
  <a href="http://sejm.gov.pl/poslowie/posel6/{$DATA.item.sejm_id}.htm" target="_blank">Sejm</a>
  <div class="inner_title_bar"></div>
  <div id="item_form"></div>
</div>
<script>
  item = new Item({$DATA.item|@json_encode}, {$DATA.zmiany|@json_encode});
</script>