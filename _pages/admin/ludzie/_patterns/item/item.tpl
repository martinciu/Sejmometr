<div id="item" class="_height_controll">
  <div class="inner_title_bar"></div>
  <div id="item_form"></div>
  <ul id="funkcje">{section name="funkcje" loop=$DATA.funkcje}{assign var="funkcja" value=$DATA.funkcje[funkcje]}
    <li>{$funkcja}</li>
  {/section}</ul>
</div>
<script>
  item = new Item({$DATA.item|@json_encode});
</script>