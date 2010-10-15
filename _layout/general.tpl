<div id="_TOPBAR"></div>
<div id="_MAIN_CONTAINER"{if $M.FULLSCREEN} class="FULLSCREEN"{/if}">
  
  <div id="_HEADER">
    <h1 id="_LOGO"><a href="/">Sejmometr</a></h1>
    <div id="_TOP_NAV_RIGHT">
      <div id="_SEARCH_BAR">
        <form action=""><input id="_MAIN_SEARCH_INPUT" type="text" /></form>
      </div>
      <ul id="_SMALL_NAV_BAR">
        {if $M.isUserLogged}
        <li><span class="login">{$M.USER.login}</span></li>
        {if $M.USER.group eq 2}{include file="/_layout/liczniki_div.tpl"}{/if}
        <li><a href="#">Profil</a></li>
        <li><a href="#">Konto</a></li>
        <li><a href="/logout?next={$M.ID}">Wyloguj</a></li>
        {else}
        <li><a href="/login">Zarejestruj się</a></li>
        <li><a href="/login">Zaloguj</a></li>
        {/if}
      </ul>
    </div>
  </div>
  
  <div id="_CONTENT">
    <div id="_CONTENT_INNER">
      <div id="_COL_LEFT">
        <div id="_MENU">
          {$M.MENU_HTML}
        </div>
      </div>
      <div id="_COL_RIGHT"{if $M.USER.group}class="{$M.USER.group}"{/if}>
        {$M.PAGE_HTML}
      </div>
    </div>
  </div>
  
  <div id="_FOOTER">
    <div id="_FOOTER_INNER">
    <div class="lfloat">Fundacja ePaństwo</div>
    <div class="rfloat">
      <a  href="#">Regulamin</a> · <a href="#">Polityka prywatności</a>
    </div>
  </div>
  
</div>
