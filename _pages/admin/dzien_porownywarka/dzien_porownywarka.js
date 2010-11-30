var enabled = true;

function init(){
  $('btnZapisz').observe('click', function(){
    if( enabled ) {
      enabled = false;
      $('btnZapisz').disable();
      $S('zapisz', dzien_id, function(data){
        if( data==4 ) { alert('Zapisałem'); } else { alert("Mamy problem:\n\n"+data); }
        enabled = true;
        $('btnZapisz').enable();
      });
    }
  });
  
  $('btnSprawdz').observe('click', function(){
    if( enabled ) {
      enabled = false;
      $('btnSprawdz').disable();
      check();
      enabled = true;
      $('btnSprawdz').enable();
    }
  });
}

function check(){
  var leq = $$('#td_a div.i').length==$$('#td_b div.i').length;
  var length = Math.min( $$('#td_a div.i').length, $$('#td_b div.i').length );
  
  var errors = false;
  for( var i=0; i<length; i++ ) {
    var td_a = $$('#td_a div.i')[i];
    var td_b = $$('#td_b div.i')[i];
    
    if( td_a.readAttribute('typ')!=td_b.readAttribute('typ') || td_a.readAttribute('autor')!=td_b.readAttribute('autor') || td_a.innerHTML!=td_b.innerHTML ) {
      td_a.addClassName('error');
      alert(i);
      errors = true;
      break;
    }
  }
  if( !errors ) alert('No errors');
  if( !leq ) alert('Lengths not equal');
}