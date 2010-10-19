$M.addInitCallback(function(){
  $('btnAnuluj').observe('click', anuluj);
  $('btnPublikuj').observe('click', publikuj);
});

var _fields = ['tytul', 'opis', 'tresc'];
var _enabled = true;

function goToBlog(){
  location = '/blog';
}

function getParams(){
  CKEDITOR.instances['editor'].updateElement();
  var params = $('form').serialize(true);
  _fields.each( function(field){
    this[field] = this[field].strip();
  }.bind(params) );
  return params;
}

function isFormEmpty(){
  var params = getParams();
  return params['tytul']=='' || params['opis']=='' || params['tresc'].stripTags().gsub('&nbsp;', '').strip()=='';
}

function anuluj(){
  if( _enabled ) {
	  if( isFormEmpty() ) {
	    goToBlog();
	  } else if( confirm('Czy na pewno chcesz anulować?') ) goToBlog();
  }
}

function publikuj(){
  if( _enabled ) {

	  if( isFormEmpty() ) {
	    alert('Brak treści');
	  } else {
	    _enabled = false;
	    $('btnPublikuj').update('zapisuje...');
	    $POST_SERVICE('blog/publikuj_post', getParams(), function(result){
	      $('btnPublikuj').update('Publikuj');
	      if( result==1 ) {
	        alert('Post został opublikowany');
	        goToBlog();
	      } else {
	        alert('Wystąpił błąd');
	        _enabled = true;
	      }
	    });
	  }
  
  }
}