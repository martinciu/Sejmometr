<?
	/* return codes
	  
	  2 - wrong id
	  3 - not saved
	  4 - saved
	  
	*/

  $id = $_PARAMS['id'];
  if( strlen($id)!=5 ) { return 2; }
  
  $projekt = array(
    'druk_id' => $_PARAMS['druk_id'],
    'autor_id' => $_PARAMS['autor_id'],
    'typ_id' => $_PARAMS['typ_id'],
    'tytul' => addslashes( $_PARAMS['tytul'] ),
    'opis' => addslashes( $_PARAMS['opis'] ),
    'akcept' => '1',
  );
  
  $this->DB->update_assoc('projekty', $projekt, $id);
  $rows = $this->DB->affected_rows;
  
  if( $rows!=1 ) return 3;
  
  $this->S('liczniki/nastaw/projekty-wlasciwosci');
  $this->S('liczniki/nastaw/projekty-etapy');
  return 4;
?>