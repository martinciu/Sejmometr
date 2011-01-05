function init(){
  $$('a.dokument.reader').each(function(a){
    
    var doc_id = a.readAttribute('doc_id');
    var doc_key = a.readAttribute('doc_key');
    var title = a.readAttribute('title');
    
    a.observe('click', pokaz_dokument.bind(null, doc_id, doc_key, title));
    
    var e = a.up('.e');
    if(e){
      var a = e.down('.tytul a');
      if(a) {
        a.observe('click', pokaz_dokument.bind(null, doc_id, doc_key, title));
      }
      
      if( !e.hasClassName('podpisanie') ) {
	      var il = e.down('.ikona_legislacyjna');
	      if( il ) il.wrap($ANCHOR()).observe('click', pokaz_dokument.bind(null, doc_id, doc_key, title));
      }
    }
    
  });
}