<div id="_MAIN_CONTAINER">
 
  <div id="_HEADER">
    {if $M.ID ne "oportalu"}<p id="_POWITANIE">
      Witaj na portalu Sejmometr! Znajdziesz tu informacje o pracach Sejmu i zmianach prawa w Polsce. <a href="/oportalu">więcej &raquo;</a>
    </p>{/if}
    <a href="/" id="_LOGO_A" ><img id="_LOGO_IMG" src="/g/_LOGO.gif" /></a>
    <ul id="_MAIN_MENU_UL">
      {section name="menu" loop=$M.TEMPMENU}{assign var="o" value=$M.TEMPMENU[menu]}
	      <li{if $smarty.get._TYPE eq $o[0]} class="selected"{/if}><a href="/{$o[0]}">{$o[1]}</a></li>
      {/section}
    </ul>
  </div>
  
  <div id="_CONTAINER">
    {$M.PAGE_HTML}
  </div>
  
  <div id="_FOOTER">
    <a href="#">Fundacja ePaństwo</a><span class="separator">·</span><a href="/oportalu">O portalu</a>
  </div>
</div>
