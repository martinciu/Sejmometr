<div id="punkt" class="_height_controll">
  <div class="inner_title_bar">
    <p>{$DATA.item.data}<span class="separator">·</span>Ilość wypowiedzi: <b>{$DATA.item.ilosc_wypowiedzi}</b></p>
    {if $DATA.item.typ && $DATA.item.subtypy|@count ne 1}<p class="error">Prawdopodobny błąd subtypów!</p>{/if}
  </div>
  <div id="item_form"></div>
  
  <div id="projekty">
  {assign var="projekty" value=$DATA.projekty}
  {section name="projekty" loop=$projekty}{assign var="projekt" value=$projekty[projekty]}
	  <div class="projekt">
	    {if $projekt.dokument_id}<a target="projekty" class="dokument e" href="/admin/projekty/wlasciwosci#id={$projekt.id}"><img class="d" dokument_id="{$projekt.dokument_id}" src="/d/4/{$projekt.dokument_id}.gif" /></a>{/if}
	    <div class="c">
	      <p class="t"><a target="projekty" href="/admin/projekty/wlasciwosci#id={$projekt.id}">{$projekt.tytul}</a></p>
	      <p>Druk numer: <b>{$projekt.numer}</b>, autor: <span class="a">{$projekt.autor}</b></span></p>
	    </div>
	  </div>
  {/section}
  </div>
  
</div>
<script>
  item = new Item({$DATA.item|@json_encode}, {$DATA._typy_options|@json_encode});
</script>