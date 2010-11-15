{if $can_manage}{literal}
  Event.observe(window, 'keypress', function(event){
    if( event.ctrlKey && event.charCode==100 ) {
      var elements = $$('._admin_hidden');
			if( elements ) elements.invoke('toggle');
    }
  }.bind(this));

  
  function usun_post(id){
    if( confirm('Czy na pewno chcesz usunąć ten post?') ) {
      $('btnUsun').update('Usuwam...');
      $S('blog/usun', id, function(result){
        $('btnUsun').update('Usuń');
        if( result==1 ) {
          alert('Post został usunięty');
          location = '/blog';
        } else {
          alert('Wystąpił błąd');
        }
      });
    }
  }
{/literal}{/if}