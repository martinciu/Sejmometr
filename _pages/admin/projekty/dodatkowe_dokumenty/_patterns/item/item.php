<?
  $id = $_PARAMS['id'];

  
  $item = $this->DB->selectAssoc("SELECT projekty_dokumenty.id, projekty_dokumenty.druk_id, projekty_dokumenty.projekt_id, projekty_dokumenty.tytul, dokumenty.id as 'dokument_id', dokumenty.akcept as dokument_akcept, dokumenty.scribd_doc_id, dokumenty.scribd_access_key FROM projekty_dokumenty JOIN druki ON projekty_dokumenty.druk_id=druki.id JOIN dokumenty ON druki.dokument_id=dokumenty.id WHERE projekty_dokumenty.id='$id'");
  
  
  $result['item'] = $item;
  return $result;
?>