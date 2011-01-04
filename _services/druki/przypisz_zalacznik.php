<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;

	list( $typ_id, $autorA_id, $zalacznik ) = $this->DB->selectRow("SELECT typ_id, autorA_id, zalacznik FROM druki WHERE id='$id'");
	if( $zalacznik!='1' ) return false;
	
  $this->DB->update_assoc('druki', array('przypisany'=>'0'), $id);
  
  $empty = true;



  // STANOWISKA RZĄDOWE
  
  if( $typ_id==7 && $autorA_id=='Rzad' ) {
    
    $empty = false;
    $projekty_ids = $this->DB->selectValues("SELECT projekt_id FROM projekty_druki WHERE druk_id IN (SELECT druk FROM druki_zalaczniki WHERE zalacznik='$id')");
    if( !empty($projekty_ids) ) {
	    foreach( $projekty_ids as $projekt_id ) {
	      $this->DB->insert_ignore_assoc('projekty_stanowiska_rzadu', array(
	        'projekt_id' => $projekt_id,
	        'druk_id' => $id,
	      ));
	    }
      $this->S('projekty/policz_opinie', $projekt_id);
	    $this->DB->update_assoc('druki', array('przypisany'=>'1'), $id);
    }





  // OPINIE

  } elseif( $typ_id==4 ) {
    
    $empty = false;
    $projekty_ids = $this->DB->selectValues("SELECT projekt_id FROM projekty_druki WHERE druk_id IN (SELECT druk FROM druki_zalaczniki WHERE zalacznik='$id')");
    if( !empty($projekty_ids) ) {
	    foreach( $projekty_ids as $projekt_id ) {
	      $this->DB->insert_ignore_assoc('projekty_opinie', array(
	        'projekt_id' => $projekt_id,
	        'druk_id' => $id,
	      ));
	    }
      $this->S('projekty/policz_opinie', $projekt_id);
	    $this->DB->update_assoc('druki', array('przypisany'=>'1'), $id);
    }



  
  
  // AKTY WYKONAWCZE
  
  } else if( $typ_id==18 || $typ_id==26) {
    
    $empty = false;
    $projekty_ids = $this->DB->selectValues("SELECT projekt_id FROM projekty_druki WHERE druk_id IN (SELECT druk FROM druki_zalaczniki WHERE zalacznik='$id')");
    if( !empty($projekty_ids) ) {
	    foreach( $projekty_ids as $projekt_id ) {
	      $this->DB->insert_ignore_assoc('akty_wykonawcze', array(
	        'projekt_id' => $projekt_id,
	        'druk_id' => $id,
	      ));
	    }
	    $this->DB->update_assoc('druki', array('przypisany'=>'1'), $id);
	    $this->S('projekty/dodatkowe_dokumenty/policz', $projekt_id);
    }
  
  
  
  
  // DODATKOWE DOKUMENTY
  
  } else if( $typ_id==10 || $typ_id==21 || $typ_id==25 || $typ_id==29 || $typ_id==31 ) {
    
    $empty = false;
    $projekty_ids = $this->DB->selectValues("SELECT projekt_id FROM projekty_druki WHERE druk_id IN (SELECT druk FROM druki_zalaczniki WHERE zalacznik='$id')");
    if( !empty($projekty_ids) ) {
	    foreach( $projekty_ids as $projekt_id ) {
	      $this->DB->insert_ignore_assoc('projekty_dokumenty', array(
	        'projekt_id' => $projekt_id,
	        'druk_id' => $id,
	      ));
	    }
	    $this->DB->update_assoc('druki', array('przypisany'=>'1'), $id);
	    $this->S('liczniki/nastaw/projekty_dokumenty');
    }
  
  
  
  
  
  
  
  
  // ERRATY
  
  } else if( $typ_id==20 ) {
    
    $empty = false;
    $druki_ids = $this->DB->selectValues("SELECT druk FROM druki_zalaczniki WHERE zalacznik='$id'");
    if( !empty($druki_ids) ) {
	    foreach( $druki_ids as $druk_id ) {
	      $this->DB->insert_ignore_assoc('erraty', array(
	        'druk_id' => $druk_id,
	        'errata_id' => $id,
	      ));
	    }
	    $this->DB->update_assoc('druki', array('przypisany'=>'1'), $id);
    }
    
 
 
  
  
  
  // IGNOROWANE
  
  } else if( $typ_id==33 ) {
    $this->DB->update_assoc('druki', array('przypisany'=>'1'), $id);


  
  
  
  // ZMIANY WNIOSKODAWCY
  
  } else if( $typ_id==14 ) {
    
    $empty = false;
    $projekty_ids = $this->DB->selectValues("SELECT projekt_id FROM projekty_druki WHERE druk_id IN (SELECT druk FROM druki_zalaczniki WHERE zalacznik='$id')");
    if( !empty($projekty_ids) ) {
	    foreach( $projekty_ids as $projekt_id ) {
	      $this->DB->insert_ignore_assoc('zmiany_wnioskodawcy', array(
	        'projekt_id' => $projekt_id,
	        'druk_id' => $id,
	      ));
	    }
	    $this->DB->update_assoc('druki', array('przypisany'=>'1'), $id);
    }
  }
  
  
  
  if( !$empty ) $this->S('liczniki/nastaw/druki_nieprzypisane');
 

?>