<div id="punkt" class="_height_controll">
  <div class="inner_title_bar">{$DATA.item.label}</div>
  <div id="item_form"></div>
  
</div>
<script>
  item = new Item({$DATA.item|@json_encode}, {$DATA.nazwa|@json_encode});
</script>