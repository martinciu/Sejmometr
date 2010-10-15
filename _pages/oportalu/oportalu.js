function init(){
  $('adres_form').observe('submit', adres_submit);
  $('adres_input').activate();
}

function adres_submit(){
  var adres = $F('adres_input');
  if( adres.match(/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i) ) {
    
    $('adres_form').disable();
    $S('zapisz_adres', adres, function(result){
      if( result===true ) {
	      alert('Dziękujemy - Twój adres został zapisany');
	      $('adres_input').value='';
	      $('adres_form').enable();
      } else adres_blad();
    }, adres_blad);
    
  } else {
    alert('To nie jest prawidłowy adres email');
  }
}

function adres_blad(){
	alert("Twój adres nie został zapisany\nSpróbuj ponownie");
	$('adres_form').enable();
	$('adres_input').activate();
}