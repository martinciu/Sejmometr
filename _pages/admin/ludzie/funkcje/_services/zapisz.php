<?
  $id = (int) $_PARAMS['id'];
  $funkcja_nazwa = trim( $_PARAMS['funkcja'] );
  $czlowiek_nazwa = trim( $_PARAMS['nazwa'] );
  $fraza = $funkcja_nazwa;
  
  if( $id==0 || empty($funkcja_nazwa) || empty($czlowiek_nazwa) || empty($fraza) ) return 2;

  
  
  if( $this->DB->selectCount("SELECT COUNT(*) FROM wypowiedzi_funkcje WHERE nazwa='$funkcja_nazwa'") ) {
    $funkcja_id = $this->DB->selectValue("SELECT id FROM wypowiedzi_funkcje WHERE nazwa='$funkcja_nazwa'");
  } else {
    $this->DB->insert_assoc("wypowiedzi_funkcje", array(
	    'nazwa' => $funkcja_nazwa,
	    'fraza' => $fraza,
	  ));
	  $funkcja_id = $this->DB->insert_id;
  }



  if( $this->DB->selectCount("SELECT COUNT(*) FROM ludzie WHERE nazwa='$czlowiek_nazwa'") ) {
    $czlowiek_id = $this->DB->selectValue("SELECT id FROM ludzie WHERE nazwa='$czlowiek_nazwa'");
  } else {
    $czlowiek_id = $this->S('ludzie/generuj_id', $czlowiek_nazwa);
    if( $this->DB->selectCount("SELECT COUNT(*) FROM ludzie WHERE id='$czlowiek_id'") ) return 4;
    $this->DB->insert_assoc('ludzie', array(
      'id' => $czlowiek_id,
      'nazwa' => $czlowiek_nazwa,
      'fraza' => $czlowiek_nazwa,
    ));
  }
 
  
  
  $wyp_ids = $this->DB->selectValues("SELECT wyp_id FROM `wypowiedzi_id-nierozpoznani_autorzy` WHERE autor_id='$id'");
  foreach( $wyp_ids as $wyp_id ) {
    $this->DB->update_assoc('wypowiedzi', array(
      'autor_id' => $czlowiek_id,
	    'funkcja_id' => $funkcja_id,
	    '_r' => '5',
    ), $wyp_id);
    $this->DB->q("DELETE FROM `wypowiedzi_id-nierozpoznani_autorzy` WHERE wyp_id='$wyp_id' AND autor_id='$id'");
  }
  
  $this->DB->q("DELETE FROM wypowiedzi_nierozpoznani_autorzy WHERE id='$id'");
  
  return 5;
?>