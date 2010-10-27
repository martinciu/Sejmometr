<?
  $id = $_PARAMS['id'];
  $result = array();
  
  
  
  $item = $this->DB->selectAssoc("SELECT projekty_teksty.id, druki.numer, dokumenty.akcept as dokument_akcept, dokumenty.scribd_doc_id, dokumenty.scribd_access_key, druki.dokument_id, projekty_teksty.autorzy, projekty_teksty.tresc, projekty_teksty.uzasadnienie, projekty_teksty.osr FROM projekty_teksty LEFT JOIN druki ON projekty_teksty.id=druki.id LEFT JOIN dokumenty ON druki.dokument_id=dokumenty.id WHERE projekty_teksty.id='$id'");
  $dokument_id = $item['dokument_id'];
  
  $item['txt'] = @file_get_contents( ROOT.'/dokumenty_txt/'.$dokument_id.'.txt' );
  if( $item['txt']!==false ) {
    $item['txt'] = str_replace("\n", '<p>', $item['txt']);
    $item['txt'] = str_replace("\r", '<p>', $item['txt']);
    $item['txt'] = '<p>'.$item['txt'];
  }
  $item['pdf_size'] = round( filesize( ROOT.'/dokumenty/'.$dokument_id.'.pdf' ) / 1024 / 102.4 ) / 10;
  
  
  
  $result['item'] = $item;
  return $result;
?>