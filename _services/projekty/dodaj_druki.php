<?
  list( $projekt_id, $druki ) = $_PARAMS;
  if( strlen($projekt_id)!=5 ) return false;
  if( !is_array($druki) || empty($druki) ) return false;
  
  
  
  $druki_ids = array();
  foreach( $druki as $druk_nr ) {
    $druk_id = $this->DB->selectValue("SELECT id FROM druki WHERE numer='$druk_nr'");
    if( empty($druk_id) ) {
      $this->DB->insert_ignore_assoc('projekty_druki_nierozpoznane', array(
        'projekt_id' => $projekt_id,
        'druk_nr' => $druk_nr,
      ));
    } else {
      $druki_ids[] = $druk_id;
      $this->DB->insert_ignore_assoc('projekty_druki', array(
        'projekt_id' => $projekt_id,
        'druk_id' => $druk_id,
      ));
      $this->DB->q("DELETE FROM projekty_druki_nierozpoznane WHERE `projekt_id`='$projekt_id' AND `druk_nr`='$druk_nr'");
    }
  }
  
  $this->S('druki/dodaj_projekt_punkty', array($projekt_id, $druki_ids));
?>