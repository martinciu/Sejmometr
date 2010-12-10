<?
  $projekt_id = $_PARAMS['id'];
  if( strlen($projekt_id)!=5 ) return false;
  
  $_typ = $PARAMS['typ'];
  $etapy = $_PARAMS['etapy'];
  
  $_etapy_typy = array('druk', 'czytanie_komisje', 'wypowiedzi', 'glosowania', 'skierowanie', 'wyrok', 'aklamacja', 'wypowiedzi_bw');
  
  $data = $this->S('projekty/pobierz_dane_do_zapisu', $_PARAMS);
  if( $data['error'] ) return $data;
  
  
  
  
  
  $multidebaty = array();
  $multidebaty_ids = $this->DB->selectValues("SELECT projekty_etapy.etap_id FROM projekty_etapy JOIN multidebaty ON projekty_etapy.etap_id=multidebaty.id WHERE projekt_id='$projekt_id' AND typ_id=2");
  foreach( $multidebaty_ids as $mdid ) $multidebaty[ $mdid ] = implode( ',', $this->DB->selectValues("SELECT projekt_id FROM projekty_etapy WHERE typ_id=2 AND etap_id='$mdid' ORDER BY projekt_id ASC") );
  
  $model = array();
  for( $i=0; $i<count($etapy); $i++ ) {
    $etap = $etapy[$i];
    $typ_id = array_search($etap['typ'], $_etapy_typy);
    
    if( $typ_id===false ) { return array('error'=>'5'); } else {      
      $item = array('typ_id'=>$typ_id, 'subtyp'=>0);
      switch( $typ_id ){
        
        case 1: { // czytanie_komisje
          $item['etap_id'] = $etap['id'] ? $etap['id'] : $this->S('projekty/dodaj_czytanie_komisje', array($projekt_id, $etap)); break;
        }
        
        case 4: { // skierowanie
          $item['etap_id'] = $etap['id'] ? $etap['id'] : $this->S('projekty/dodaj_skierowanie', array($projekt_id, $etap)); break;
        }
        
        case 6: { // aklamacja
          $item['etap_id'] = $etap['id'] ? $etap['id'] : $this->S('projekty/dodaj_aklamacje', array($projekt_id, $etap)); break;
        }
        
        case 7: { // wypowiedzi_bw
          $item['etap_id'] = $etap['id'] ? $etap['id'] : $this->S('projekty/dodaj_wypowiedzi_bw', array($projekt_id, $etap)); break;
        }
        
        default: {
          $item['etap_id'] = $etap['id'];
          if( $etap['debata_typ'] ) $item['subtyp'] = $etap['debata_typ'];
        }
      }
      $model[] = $item;
    }
  }
  
  $data['data_zapisania_etapow'] = 'NOW()';
  $data['html_zmiana'] = '0';
  $data['alert'] = '0';
  
  $this->DB->q("DELETE FROM projekty_etapy WHERE projekt_id='$projekt_id'");
  
  $debaty_ids = array();
  for( $i=0; $i<count($model); $i++ ) {
    $this->DB->insert_assoc('projekty_etapy', array(
	    'projekt_id' => $projekt_id,
	    'typ_id' => $model[$i]['typ_id'],
	    'subtyp' => $model[$i]['subtyp'],
	    'etap_id' => $model[$i]['etap_id'],
	    'ord' => $i,
	  ));
	  if( $this->DB->affected_rows ) {
	    switch( $model[$i]['typ_id'] ) {
	      case 0: {
	        $this->DB->q("UPDATE projekty_druki SET przypisany='1' WHERE projekt_id='$projekt_id' AND druk_id='".$model[$i]['etap_id']."'");
	        break;
	      }
	      case 2: {
	        $debaty_ids[] = $model[$i]['etap_id'];
	        $this->DB->q("UPDATE projekty_punkty_wypowiedzi SET przypisany='1' WHERE projekt_id='$projekt_id' AND punkt_id='".$model[$i]['etap_id']."'");
	        break;
	      }
	      case 3: {
	        $this->DB->q("UPDATE projekty_punkty_glosowania SET przypisany='1' WHERE projekt_id='$projekt_id' AND punkt_id='".$model[$i]['etap_id']."'");
	        break;
	      }
	      case 5: {
	        $this->DB->q("UPDATE projekty_wyroki SET przypisany='1' WHERE projekt_id='$projekt_id' AND wyrok_id='".$model[$i]['etap_id']."'");
	        break;
	      }
	    }
	  }
  }
  
  unset( $data['typ'] );
  
  
  
  
  
  
  
  
  // MULTIDEBATY START
  foreach( $debaty_ids as $did ) {
    if( in_array($did, $multidebaty_ids) ) {
      
      if( implode(',', $this->DB->selectValues("SELECT projekt_id FROM projekty_etapy WHERE typ_id=2 AND etap_id='$did' ORDER BY projekt_id ASC"))!=$multidebaty[$did] ) $this->DB->update_assoc('multidebaty', array('akcept'=>'0'), $did);
    
    } else {
      
      if( $this->DB->selectCount("SELECT COUNT(DISTINCT(projekt_id)) FROM projekty_etapy WHERE typ_id=2 AND etap_id='$did' AND projekt_id!='$projekt_id'") ) $this->DB->insert_assoc('multidebaty', array('id'=>$did));
      
    }
  }
  // MULTIDEBATY END
  
  
  
  
  
  
  
  $this->DB->update_assoc('projekty', $data, $projekt_id);
  
  
  $this->S('liczniki/nastaw/projekty-wlasciwosci');
  $this->S('liczniki/nastaw/projekty-etapy');
  $this->S('liczniki/nastaw/multidebaty');
  
  return array(
    'status' => 4,
    'model' => $model,
    'status_slowny' => $data['status_slowny'],
  );
?>