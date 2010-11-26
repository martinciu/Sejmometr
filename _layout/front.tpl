<div id="_MAIN_CONTAINER">
 
  <div id="_HEADER">
    {if !$nie_pokazuj_powitania}<p id="_POWITANIE">
      Witaj na portalu Sejmometr! Znajdziesz tu informacje o pracach Sejmu i zmianach prawa w Polsce. <a href="/oportalu">więcej &raquo;</a>
    </p>{/if}
    <a href="/" id="_LOGO_A" ><img id="_LOGO_IMG" src="/g/_LOGO.gif" /></a>
    <ul id="_MAIN_MENU_UL" class="_MAIN_MENU_UL">
      {section name="menu" loop=$M.FRONT_MENU}{assign var="o" value=$M.FRONT_MENU[menu]}
	      <li {if $o.selected}class="selected"{/if}><a href="/{$o.id}">{$o.label}</a></li>
      {/section}
    </ul>
    <ul class="_MAIN_MENU_UL r">
      <li{if $M.ID eq 'oportalu' || $M.FRONT_MENU_SELECTED eq 'oportalu'} class="selected"{/if}><a href="/oportalu">O portalu</a></li>
    </ul>
  </div>
  
  <div id="_SUBMENUS">
  {section name="menus" loop=$M.FRONT_SUBMENUS}
    {assign var="menu" value=$M.FRONT_SUBMENUS[menus]}
    {assign var="menuid" value=$menu.id}
    {assign var="show" value=$menu.show}
    {assign var="menu" value=$menu.menu}
    <ul class="_SUBMENU_MENU_UL" menuid="{$menuid}"{if !$show} style="display: none;"{/if}>
      {section name="menu" loop=$menu}{assign var="o" value=$menu[menu]}
      <li {if $o.selected}class="selected"{/if}><a href="/{$o.id}">{$o.label}</a></li>
      {/section}
    </ul>
  {/section}
  </div>
  
  <div id="_CONTAINER">
    {$M.PAGE_HTML}
  </div>
  
  <div id="_FOOTER">
    <a href="#">Fundacja ePaństwo</a><span class="separator">·</span><a href="/oportalu">O portalu</a><span class="separator">·</span><a href="/blog">Blog</a><span class="separator">·</span><a href="/media">Dla mediów</a>
  </div>
</div>
