<?
  $id = $_PARAMS['id'];
  $pola = $_PARAMS['pola'];
  $zmiany = $_PARAMS['zmiany'];
  
  list($sejm_id, $_image_md5, $_akcept) = $this->DB->selectRow("SELECT sejm_id, image_md5, akcept FROM poslowie WHERE id='$id'");
  if( $_akcept=='1' ) return 2;
  
  $imie = $pola['imie'];
  $drugie_imie = $pola['drugie_imie'];
  $nazwisko = $pola['nazwisko'];
  $nazwa = $imie.' '.$nazwisko;
  $aktywny = $pola['data_wygasniecia']=='0000-00-00' ? '1' : '0';
  
  /*
  $czlowiek_id = $this->DB->selectValues("SELECT id FROM ludzie WHERE sejm_id='$sejm_id' AND imie='$imie' AND drugieImie='$drugie_imie' AND nazwisko='$nazwisko'");
  if( count($czlowiek_id)==0 ) {
  
    $czlowiek_id = str_replace(' ', '-', $nazwa);
    $this->DB->insert_ignore_assoc('ludzie', array('id'=>$czlowiek_id));
    if( !$this->DB->affected_rows ) return 7;
  
  } elseif( count($czlowiek_id)==1 ) {
  
    $czlowiek_id = $czlowiek_id[0];
  
  } else return 3;
  */
  $czlowiek_id = 'Jan-Bury_';
  
  
  // $this->DB->q("UPDATE poslowie SET id='$czlowiek_id' WHERE id='$id'");
  

  
  
  
  $pola['id'] = $czlowiek_id;
  $pola['akcept'] = '1';
  $pola['update'] = '0';
  $pola['aktywny'] = $aktywny;
  $pola['nazwa'] = $nazwa;
  $pola['data_akceptu'] = 'NOW()';
  
  $this->DB->update_assoc('poslowie', $pola, $czlowiek_id);
  if( true || $this->DB->affected_rows ) {
    
	      
    
    $this->DB->q("UPDATE poslowie_pola SET posel_id='$czlowiek_id' WHERE posel_id='$id'");
    if( !empty($zmiany) ) $this->DB->q("UPDATE poslowie_pola SET akcept='1' WHERE posel_id='$czlowiek_id' AND (id='".implode("' OR id='", $zmiany)."')");
    
    
    
    if( $_image_md5!=$pola['image_md5'] ) {
	    if( !$this->S('zmien_avatar', $czlowiek_id) ) return 6;
	  }
    
   
  } else return 5;
  
  return array(4, $czlowiek_id);
?>