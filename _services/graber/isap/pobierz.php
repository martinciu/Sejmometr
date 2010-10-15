<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
  
  $sejm_id = $this->DB->selectValue("SELECT sejm_id FROM isap WHERE id='$id'");
  $this->DB->q("UPDATE isap SET `status`='1' WHERE id='$id'");
  
  require_once(ROOT.'/_lib/SejmParser.php');
  $SP = new SejmParser();
  
  $data = $SP->isap_info($sejm_id);
   
  $typ = ($data['organ_wydajacy']=='TRYBUNAŁ KONSTYTUCYJNY') ? 'wyrok' : 'isap';
   
  $params = array(
    'typ' => $typ,
    'gid' => $id,
  );
  
  if( $data['tekst_ogloszony'] ) {
    $data['dokument_ogloszony'] = $this->S('dokumenty/dodaj', array_merge($params, array('download_url'=>'http://isap.sejm.gov.pl/Download?id='.$sejm_id.'&type=2', 'plik'=>$id.'2.pdf')));
    unset($data['tekst_ogloszony']);
  }
  
  if( $data['tekst_aktu'] ) {
    $data['dokument_aktu'] = $this->S('dokumenty/dodaj', array_merge($params, array('download_url'=>'http://isap.sejm.gov.pl/Download?id='.$sejm_id.'&type=1', 'plik'=>$id.'1.pdf')));
    unset($data['tekst_aktu']);
  }
  
  if( $data['tekst_ujednolicony'] ) {
    $data['dokument_ujednolicony'] = $this->S('dokumenty/dodaj', array_merge($params, array('download_url'=>'http://isap.sejm.gov.pl/Download?id='.$sejm_id.'&type=3', 'plik'=>$id.'3.pdf')));
    unset($data['tekst_ujednolicony']);
  }
  
  if( $typ=='isap' ) {
        
    $data['status'] = '2';
	  $data['data_pobrania'] = 'NOW()';
	  $this->DB->update_assoc('isap', $data, $id);
  
  } elseif( $typ=='wyrok' ) {
    
    $wyrok_data = array(
      'id' => $id,
      'sejm_id' => $sejm_id,
			'numer' => $data['numer'],
			'tytul' => $data['tytul'],
			'data_obowiazywania' => $data['data_obowiazywania'],
			'data_ogloszenia' => $data['data_ogloszenia'],
			'data_wejscia_w_zycie' => $data['data_wejscia_w_zycie'],
			'data_wydania' => $data['data_wydania'],
			'uwagi' => $data['uwagi'],
			'dokument_ogloszony' => $data['dokument_ogloszony'],
			'dokument_aktu' => $data['dokument_aktu'],
			'dokument_ujednolicony' => $data['dokument_ujednolicony'],
    );
    
    $this->DB->insert_assoc('wyroki_tk', $wyrok_data);
    if( $this->DB->affected_rows==1 ) {
    
	    $projekty = $this->DB->selectValues("SELECT projekt_id FROM projekty_isap WHERE isap_id='$id'");
	    foreach( $projekty as $projekt_id ) {
	      $this->DB->insert_ignore_assoc('projekty_wyroki', array(
	        'projekt_id' => $projekt_id,
	        'wyrok_id' => $id,
	      ));
	    }
	    $this->DB->q("DELETE FROM projekty_isap WHERE isap_id='$id'");
	    $this->DB->q("DELETE FROM isap WHERE id='$id'");
      
    }
  }
  
  $this->S('liczniki/nastaw/wyroki');
  
  
  

    
  
?>