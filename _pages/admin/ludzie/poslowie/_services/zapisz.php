<?
  $id = $_PARAMS['id'];
  $pola = $_PARAMS['pola'];
  $zmiany = $_PARAMS['zmiany'];
  
  list($sejm_id, $_image_md5, $klub_a) = $this->DB->selectRow("SELECT sejm_id, image_md5, klub FROM poslowie WHERE id='$id'");

  
  $imie = $pola['imie'];
  $drugie_imie = $pola['drugie_imie'];
  $nazwisko = $pola['nazwisko'];
  $nazwa = $imie.' '.$nazwisko;
  $aktywny = $pola['data_wygasniecia']=='0000-00-00' ? '1' : '0';
  
  
  
  $check = $this->DB->selectCount("SELECT COUNT(*) FROM ludzie WHERE id='$id'");
  if( $check==0 ) { return 10; }
  elseif( $check==1 ) { $czlowiek_id = $id; }
  else { return 11; }
  
  

  

  
  
  
  $pola['akcept'] = '1';
  $pola['update'] = '0';
  $pola['aktywny'] = $aktywny;
  $pola['nazwa'] = $nazwa;
  $pola['data_akceptu'] = 'NOW()';
  
  $this->DB->update_assoc('poslowie', $pola, $czlowiek_id);
  if( $this->DB->affected_rows ) {
    
    
    
    $klub_b = $pola['klub'];
    $this->S('kluby/stats', $klub_a);
    if( $klub_b!=$klub_a ) $this->S('kluby/stats', $klub_b);
	      
    
    if( !empty($zmiany) ) $this->DB->q("UPDATE poslowie_pola SET akcept='1' WHERE posel_id='$czlowiek_id' AND (id='".implode("' OR id='", $zmiany)."')");
    
    if( $_image_md5!=$pola['image_md5'] ) if( !$this->S('zmien_avatar', $czlowiek_id) ) return 6;
	  
	  $this->S('liczniki/nastaw/poslowie');
    
    
    
   
  } else return 5;
  
  return array(4, $czlowiek_id);
?>