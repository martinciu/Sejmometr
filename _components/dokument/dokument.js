function pokaz_dokument(doc_id, doc_key, title){
  var div = new Element('div');
  var div_id = div.identify();
  var height = Math.min( document.viewport.getHeight(), 800 )-70;
  
  $M.addLightboxShow(div, {title: title, width: 700, height: height});
  
  var scribd_doc = scribd.Document.getDoc(doc_id, doc_key);
	scribd_doc.addParam('jsapi_version', 1);
	scribd_doc.addParam('width', 700);
	scribd_doc.addParam('height', height);
	scribd_doc.addParam('auto_size', false);
	
	scribd_doc.write(div_id);
}