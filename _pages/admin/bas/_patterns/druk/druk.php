<?
  $id = $_PARAMS['id'];
  $druk = $this->DB->selectAssoc("SELECT bas.akcept, bas.id, bas.tytul, bas.data, dokumenty.akcept as dokument_akcept, dokumenty.scribd_doc_id, dokumenty.scribd_access_key, bas.dokument_id, dokumenty.plik FROM bas LEFT JOIN dokumenty ON bas.dokument_id=dokumenty.id WHERE bas.id='$id'"); 
  
  $txt_file = ROOT.'/dokumenty_txt/'.$druk['dokument_id'].'.txt';
  if( file_exists($txt_file) ) $druk['txt'] = substr( file_get_contents($txt_file), 0, 250 );
  
  
  $projekty = array();
  
  $projekty_data = $this->DB->selectAssocs("SELECT projekty.id, projekty.tytul, projekty.sejm_id, projekty.autor_id, druki_autorzy.autor, druki.numer FROM projekty_bas LEFT JOIN projekty ON projekty_bas.projekt_id=projekty.id LEFT JOIN druki ON projekty.druk_id=druki.id LEFT JOIN druki_autorzy ON projekty.autor_id=druki_autorzy.id WHERE projekty_bas.bas_id='$id'");
  foreach( $projekty_data as $projekt ) $projekty[] = $projekt['id'];
  $druk['projekty'] = $projekty;  
  
  
  
  $result['projekty_data'] = $projekty_data;
  $result['druk'] = $druk;
  return $result;
?>