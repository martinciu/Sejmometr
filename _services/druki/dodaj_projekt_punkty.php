<?
  list($projekt_id, $druki_ids) = $_PARAMS;
  
  if( strlen($projekt_id)!=5 ) return false;
  if( !is_array($druki_ids) || empty($druki_ids) ) return false;
  
  $nowe_wypowiedzi = false;
  $nowe_glosowania = false;
	  
  // PUNKTY WYPOWIEDZI
  $punkty = $this->DB->selectValues("SELECT DISTINCT(punkt_id) FROM punkty_wypowiedzi_druki WHERE druk_id='".implode("' OR druk_id='", $druki_ids)."'");
  foreach($punkty as $punkt){
    $this->DB->insert_ignore_assoc('projekty_punkty_wypowiedzi', array(
      'projekt_id' => $projekt_id,
      'punkt_id' => $punkt,
    ));
    if( $this->DB->affected_rows ) $nowe_wypowiedzi = true;
  }
  
  // PUNKTY GŁOSOWANIA
  $punkty = $this->DB->selectValues("SELECT DISTINCT(punkt_id) FROM punkty_glosowania_druki WHERE druk_id='".implode("' OR druk_id='", $druki_ids)."'");
  foreach($punkty as $punkt){
    $this->DB->insert_ignore_assoc('projekty_punkty_glosowania', array(
      'projekt_id' => $projekt_id,
      'punkt_id' => $punkt,
    ));
    if( $this->DB->affected_rows ) $nowe_glosowania = true;
  }
  
  if( $nowe_wypowiedzi || $nowe_glosowania ) {
    $data = array();
    $data['html_zmiana'] = '1';
    $data['html_zmiana_data'] = 'NOW()';
    $this->DB->update_assoc('projekty', $data, $projekt_id);
  }
    
  
?>