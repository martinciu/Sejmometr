<div id="item" class="_height_controll">
  <div class="inner_title_bar">
    <p><b>{$DATA.item.autor}</b></p>
    <p>{$DATA.item.wyp_count|dopelniacz:'wypowiedź':'wypowiedzi':'wypowiedzi'}</p>
  </div>
  <div id="item_form"></div>
</div>
<script>
  item = new Item({$DATA.item|@json_encode});
</script>