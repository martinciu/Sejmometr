<div id="login_box">
  <form action="" method="post">
    <div class="row">
      <label for="login" class="fortext">Login:</label><input name="login" type="text" maxlength="25" value="{$smarty.request.login}" />
    </div>
    <div class="row">
      <label for="pass" class="fortext">Hasło:</label><input name="pass" maxlength="25" type="password" />
    </div>
    <div class="row calign">
      <input name="remember" type="checkbox" checked="checked" /><label for="remember" class="forcheckbox">zapamiętaj mnie</label>
    </div>
    <div class="row calign separator">
      <input type="submit" value="Zaloguj" />
    </div>
    {if $M.LOGIN===false}
    <div class="row error calign">
      Niepoprawny login lub hasło
    </div>
    {/if}
  </form>
</div>