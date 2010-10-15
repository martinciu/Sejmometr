<?
	/* return codes
	  
	  2 - wrong id
	  3 - not saved
	  4 - saved
	  
	*/

  $id = $_PARAMS['id'];
  if( strlen($id)!=5 ) { return 2; }
  
  
  $druk = array(
    'id' => $id,
    'zalacznik' => (string) $_PARAMS['zalacznik'],
    'data' => $_PARAMS['data'],
    'typ_id' => (int) $_PARAMS['typ_id'],
    'autorA_id' => (string) $_PARAMS['autorzy'][0],
    'autorB_id' => (string) $_PARAMS['autorzy'][1],
    'autorC_id' => (string) $_PARAMS['autorzy'][2],
    'akcept' => 1,
    'data_akceptacji' => 'NOW()',
  );
  
  $this->DB->update_assoc('druki', $druk, $id);
  $rows = $this->DB->affected_rows;
  
  if( $rows==1 ) {
        
	  if( $druk['zalacznik']==1 ) {
	    $this->DB->q("DELETE FROM druki_zalaczniki WHERE zalacznik='$id'");
	    $druki_glowne = $_PARAMS['druki_glowne'];
	    if( is_array($druki_glowne) ) foreach( $druki_glowne as $druk_g ) {
	      $this->DB->q("INSERT IGNORE INTO druki_zalaczniki (`druk`, `zalacznik`) VALUES ('$druk_g', '$id')");
	    }
	  }
	  
	  if( $druk['zalacznik']==0 ) {
	    $this->DB->q("DELETE FROM druki_zalaczniki WHERE druk='$id'");
	    $zalaczniki = $_PARAMS['zalaczniki'];
	    if( is_array($zalaczniki) ) foreach( $zalaczniki as $zalacznik ) {
	      $this->DB->q("INSERT IGNORE INTO druki_zalaczniki (`druk`, `zalacznik`) VALUES ('$id', '$zalacznik')");
	    }
	  }
	  
	  $projekty_affected = false;
	  $projekty = $_PARAMS['projekty'];
	  if( is_array($projekty) ) foreach( $projekty as $projekt ) {
	    $this->DB->insert_ignore_assoc('projekty_druki', array(
        'projekt_id' => $projekt,
        'druk_id' => $id,
        'recznie' => '1',
      ));
      if( $this->DB->affected_rows ) {
        $projekty_affected = true;
        $data = array();
		    $data['html_zmiana'] = '1';
		    $data['html_zmiana_data'] = 'NOW()';
		    $this->DB->update_assoc('projekty', $data, $projekt);
      }
      $this->S("druki/dodaj_projekt_punkty", array($projekt, array($id)));
    }
    if( $projekty_affected ) $this->S('liczniki/nastaw/projekty-etapy'); 
   
  
  } else return 3;
  
  
  $this->S('druki/oznacz_nieprzypisane');
  $this->S('liczniki/nastaw/druki');
  $this->S('liczniki/nastaw/druki_nieprzypisane');
  return 4;
?>