{literal}
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
{/literal}