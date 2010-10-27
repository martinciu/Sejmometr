<?
	/* return codes
	  
	  2 - wrong id
	  3 - not saved
	  4 - saved
	  
	*/

  $id = $_PARAMS['id'];
  if( strlen($id)!=5 ) { return 2; }
  
  $punkt = array(
    'id' => $id,
    'akcept' => '1',
    'data_akceptacji' => 'NOW()',
    'typ_id' => $_PARAMS['typ_id'],
  );
  $druki = $_PARAMS['druki'];
  
  $this->DB->update_assoc('punkty_glosowania', $punkt, $id);
  $rows = $this->DB->affected_rows;
  
  if( $rows==1 ) {
    
    $projekty = $this->DB->selectValues("SELECT DISTINCT(projekt_id) FROM projekty_druki WHERE druk_id='".implode("' OR druk_id='", $druki)."'");
    foreach( $projekty as $projekt_id ){
      $this->DB->insert_ignore_assoc('projekty_punkty_glosowania', array(
	      'projekt_id' => $projekt_id,
	      'punkt_id' => $id,
	    ));
    }
    
    $this->DB->q("DELETE FROM punkty_glosowania_druki WHERE `punkt_id`='$id'"); 
	  if( is_array($_PARAMS['druki']) ) foreach( $druki as $druk ) {
	    $this->DB->q("INSERT IGNORE INTO punkty_glosowania_druki (`punkt_id`, `druk_id`) VALUE ('$id', '$druk')");
	  }
  
  } else return 3;
  
  $this->S('liczniki/nastaw/punkty_glosowania');
  $this->S('liczniki/nastaw/projekty-etapy');
  return 4;
?>