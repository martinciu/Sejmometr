{literal}
  $M.addInitCallback(function(){
    var loginInput = $('login_box').down('input[name=login]');
    var passInput = $('login_box').down('input[name=pass]');
    var el = $F(loginInput).strip()=='' ? loginInput : passInput;
    el.activate();
  });
{/literal}