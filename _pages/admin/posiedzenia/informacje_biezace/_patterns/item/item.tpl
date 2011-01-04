<div id="punkt" class="_height_controll">
  <div class="inner_title_bar">
    <p>{$DATA.item.data}<span class="separator">·</span>Ilość wypowiedzi: <b>{$DATA.item.ilosc_wypowiedzi}</b></p>
    <p class="sejm_id">{$DATA.item.sejm_id}</p>
  </div>
  <div id="item_form"></div>
  
  <div id="funkcje">
    {section name="funkcje" loop=$DATA.funkcje}
    <p>
      {$DATA.funkcje[funkcje]}
    </p>
    {/section}
  </div>
  
</div>
<script>
  item = new Item({$DATA.item|@json_encode}, '{$DATA.text}');
</script>