<?
	/* return codes
	  
	  2 - wrong id
	  3 - not saved
	  4 - saved
	  
	*/

  $id = $_PARAMS['id'];
  if( strlen($id)!=5 ) { return 2; }
  
  $_wyp_typy = array(
    '1' => '1', // legislacja
    '2' => '5', // sprawy bieżące
    '3' => '4', // informacje bieżące
    '4' => '7', // specjalne
    '5' => '6', // ślubowania
  );
  $wyp_typ = $_wyp_typy[ $_PARAMS['typ_id'] ];
  
  $punkt = array(
    'id' => $id,
    'akcept' => '1',
    'data_akceptacji' => 'NOW()',
    'typ_id' => $_PARAMS['typ_id'],
  );
  $druki = $_PARAMS['druki'];
  
  $this->DB->update_assoc('punkty_wypowiedzi', $punkt, $id);
  $rows = $this->DB->affected_rows;
  
  if( $rows==1 ) {
    
    if( $_PARAMS['typ_id']=='4' ) $this->DB->insert_ignore_assoc('debaty_specjalne', array('id'=>$id));
    
    $projekty = $this->DB->selectValues("SELECT DISTINCT(projekt_id) FROM projekty_druki WHERE druk_id='".implode("' OR druk_id='", $druki)."'");
    foreach( $projekty as $projekt_id ){
      $this->DB->insert_ignore_assoc('projekty_punkty_wypowiedzi', array(
	      'projekt_id' => $projekt_id,
	      'punkt_id' => $id,
	    ));
    }

    
    $this->DB->q("DELETE FROM punkty_wypowiedzi_druki WHERE `punkt_id`='$id'"); 
	  if( is_array($druki) ) foreach( $druki as $druk ) {
	    $this->DB->q("INSERT IGNORE INTO punkty_wypowiedzi_druki (`punkt_id`, `druk_id`) VALUE ('$id', '$druk')");
	  }
	  
	  $this->DB->q("UPDATE wypowiedzi SET typ='$wyp_typ' WHERE punkt_id='$id'");
  
  } else return 3;
  
  $this->S('liczniki/nastaw/punkty_wypowiedzi');
  $this->S('liczniki/nastaw/projekty-etapy');
  $this->S('liczniki/nastaw/wypowiedzi');
  return 4;
?>