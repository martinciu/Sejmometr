<div id="item" class="_height_controll">
  <div class="inner_title_bar">
    {if $DATA.item.avatar eq '1'}<img class="avatar" src="/l/2/{$DATA.item.autor_id}.jpg" />{/if}
    <div class="desc">
      <p class="nazwa">{$DATA.item.nazwa}</p>
      <p class="data">{$DATA.item.data|default:"&nbsp;"}</p>
      <p class="funkcja">{if $DATA.item.funkcja_id eq false}<blink>Brak funkcji</blink>{else}<b>{$DATA.item.funkcja_label}</b>{if $DATA.item.klub_label} ({$DATA.item.klub_label}){/if}{/if}</p>
      <p class="punkt_tytul">{$DATA.item.punkt_tytul|@strip_tags|truncate:500}</p>
    </div>
  </div>
  <p class="links">
    {section name="links" loop=$DATA.item.sejm_ids}{assign var="link" value=$DATA.item.sejm_ids[links]}<a href="http://orka2.sejm.gov.pl/Debata6.nsf/main/{$link}" target="_blank">{$link}</a>{if !$smarty.section.links.last}<span class="separator">·</span>{/if}{/section}
  </p>
  <div id="item_form"></div>
</div>
<script>
  item = new Item({$DATA.item|@json_encode});
</script>