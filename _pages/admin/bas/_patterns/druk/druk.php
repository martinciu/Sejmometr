<?
  $id = $_PARAMS['id'];
  $druk = $this->DB->selectAssoc("SELECT bas.id, bas.tytul, bas.data, dokumenty.akcept as dokument_akcept, dokumenty.scribd_doc_id, dokumenty.scribd_access_key, bas.dokument_id FROM bas LEFT JOIN dokumenty ON bas.dokument_id=dokumenty.id WHERE bas.id='$id'"); 
  
  $txt_file = ROOT.'/dokumenty_txt/'.$druk['dokument_id'].'.txt';
  if( file_exists($txt_file) ) $druk['txt'] = substr( file_get_contents($txt_file), 0, 250 );
  
  
  
  
  
  $tytul = $druk['tytul'];
  if( preg_match('/\(druk sejmowy nr(.*?)\)/i', $tytul, $matches) ) {
    $druki_pattern = $matches[1];
  } elseif( preg_match('/\(druk nr(.*?)\)/i', $tytul, $matches) ) {
    $druki_pattern = $matches[1];
  } elseif( preg_match('/\(druki(.*?)\)/i', $tytul, $matches) ) {
    $druki_pattern = $matches[1];
  } elseif( preg_match('/\[druk(.*?)\]/i', $tytul, $matches) ) {
    $druki_pattern = $matches[1];
  }
  
  $druki_pattern = preg_replace('/\s+/', ' ', $druki_pattern);
  
  
  preg_match_all('/([0-9A\-]+)/i', $druki_pattern, $matches);
  for( $i=0; $i<count($matches[0]); $i++ ){
    $nr = $matches[1][$i];
    $tytul = str_replace($nr, '<span class="link" numer="'.$nr.'">'.$nr.'</span>', $tytul);
  }
  
  $druk['tytul'] = $tytul;
  $projekty = array();
  
  /*
  $projekty_data = $this->DB->selectAssocs("SELECT projekty.id, projekty.tytul, projekty.autor_id, druki_autorzy.autor, druki.numer FROM projekty_druki LEFT JOIN projekty ON projekty_druki.projekt_id=projekty.id LEFT JOIN druki ON projekty.druk_id=druki.id LEFT JOIN druki_autorzy ON projekty.autor_id=druki_autorzy.id WHERE projekty_druki.druk_id='$id'");
  $druk['projekty'] = array();
  foreach( $projekty_data as $projekt ) $druk['projekty'][] = $projekt['id'];
  $result['projekty_data'] = $projekty_data;
  */
  
  $result['druk'] = $druk;
  $result['projekty'] = $projekty;
  return $result;
?>