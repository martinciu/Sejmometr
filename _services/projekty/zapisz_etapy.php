<?
  $projekt_id = $_PARAMS['id'];
  if( strlen($projekt_id)!=5 ) return false;
  
  $_typ = $PARAMS['typ'];
  $etapy = $_PARAMS['etapy'];
  
  $_etapy_typy = array('druk', 'czytanie_komisje', 'wypowiedzi', 'glosowania', 'skierowanie', 'wyrok', 'aklamacja', 'wypowiedzi_bw');
  
  $data = $this->S('projekty/pobierz_dane_do_zapisu', $_PARAMS);
  if( $data['error'] ) return $data;
  
  
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
  
  $this->DB->q("DELETE FROM projekty_etapy WHERE projekt_id='$projekt_id'");
  
  for( $i=0; $i<count($model); $i++ ) $this->DB->insert_assoc('projekty_etapy', array(
    'projekt_id' => $projekt_id,
    'typ_id' => $model[$i]['typ_id'],
    'subtyp' => $model[$i]['subtyp'],
    'etap_id' => $model[$i]['etap_id'],
    'ord' => $i,
  ));
  
  unset( $data['typ'] );
  $this->DB->update_assoc('projekty', $data, $projekt_id);
  
  $this->S('druki/oznacz_nieprzypisane');
  
  $this->S('liczniki/nastaw/projekty-wlasciwosci');
  $this->S('liczniki/nastaw/projekty-etapy');
  $this->S('liczniki/nastaw/druki_nieprzypisane');
  
  return array(
    'status' => 4,
    'model' => $model,
    'status_slowny' => $data['status_slowny'],
  );
?>