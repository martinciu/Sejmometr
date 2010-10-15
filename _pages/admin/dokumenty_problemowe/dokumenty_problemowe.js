var scribd_doc;
var dokument_id;

function init_scribd_reader(scribd_doc_id, scribd_access_key){
  scribd.Document.getDoc(scribd_doc_id, scribd_access_key);
	scribd_doc.addParam('jsapi_version', 1);
	scribd_doc.addParam('height', mBrowser.div.getHeight()-105);
	scribd_doc.addEventListener( 'iPaperReady', function(){
	  scribd_doc.api.setZoom(.85);
	}.bind(this) );
	scribd_doc.write('scribd_reader');
}

function bas_zapisz(){
  $('bas_zapisz_btn').disable();
  $S('dokumenty/zapisz_dokument_problemowy', dokument_id, function(data){
    
    if( data==4 ) {
      mBrowser.markAsDeleted(dokument_id);
      mBrowser.loadNextItem();
    }
    $('bas_zapisz_btn').enable();
  });
}

function bas_skasuj_pobierz_projekt(){
  $('bas_skasuj_pobierz_projekt_btn').disable();
  $S('dokumenty/skasuj_pobierz_projekt', dokument_id, function(data){
    $('bas_skasuj_pobierz_projekt_btn').enable();
  });
}

var MBrowser = Class.create(MBrowser, {

  getListItemInnerHTML: function(data){
    return data['plik'];
  },
  
});
var mBrowser;