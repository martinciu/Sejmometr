<?
  require_once( ROOT.'/_lib/SejmParser.php' );
  $SP = new SejmParser();
  
  $id = $_PARAMS;
  list($sejm_id, $dokument_id, $numer, $zalacznik) = $this->DB->selectRow("SELECT sejm_id, dokument_id, numer, zalacznik FROM druki WHERE id='$id'");
  if( $zalacznik=='1' ) $glowny_druk_id = $this->DB->selectValue("SELECT druk FROM druki_zalaczniki WHERE zalacznik='$id'");
  
  $druk = $SP->druki_info($sejm_id);
  $response_status = $SP->response_status;
  
  if( $response_status!=200 ) return $this->DB->update_assoc('druki', array('response_status'=>$response_status), $id);
  
  $druk_data = array(
    'numer' => $druk['numer'],
    'data' => $druk['data'],
    'tytul_oryginalny' => addslashes( $druk['tytul'] ),
    'ilosc_dokumentow' => count($druk['pliki']),
    'data_sprawdzenia' => 'NOW()',
    'response_status' => 200,
  );
  
  if( stripos($druk_data['tytul_oryginalny'], 'do druku')!==false ) { $druk_data['zalacznik'] = '1'; }
  
  
  
  
  if( count($druk['pliki']) ) {
  
	  for( $i=0; $i<count($druk['pliki']); $i++ ) {
	    $item = $druk['pliki'][$i];
		  $_dokument_id = $this->DB->selectValue("SELECT id FROM dokumenty WHERE typ='druk' AND plik='".$item[0]."'");
		  if( !$_dokument_id ) $_dokument_id = $this->S('dokumenty/dodaj', array(
		    'typ' => 'druk',
			  'download_url' => $item[1],
			  'plik' => $item[0],
		  ));
		  $druk['pliki'][$i][2] = $_dokument_id;
	  }
  
    
    
    
    $item = array_shift( $druk['pliki'] );
    if( $dokument_id ) {
    
      if( $dokument_id!=$item[2] ) {
        $druk_data['akcept'] = '0';
        $druk_data['dokument_id'] = $item[2];
        $this->DB->update_assoc('dokumenty', array('gid'=>$id), $item[2]);
      }
    
    } else {
      
      $druk_data['dokument_id'] = $item[2];
      $this->DB->update_assoc('dokumenty', array('gid'=>$id), $item[2]);
      
    }
  
  
    
    
    $sztuczne_dokumenty = $this->DB->selectValues("SELECT druki.dokument_id FROM druki_zalaczniki JOIN druki ON druki_zalaczniki.zalacznik=druki.id WHERE druki_zalaczniki.druk='$id' AND druki.sztuczny='1'");
    
    for( $i=0; $i<count($druk['pliki']); $i++ ) {
	    $item = $druk['pliki'][$i];
	    
	    if( !in_array($item[2], $sztuczne_dokumenty) ) {
		    $_druk_id = $this->DB->insert_assoc_create_id('druki', array(
		      'sejm_id' => md5( $item[1] ),
		      'sztuczny' => '1',
		      'dokument_id' => $item[2],
		      'ilosc_dokumentow' => 1,
		      'zalacznik' => '1',
		      'numer' => 'do druku '.$numer,
		    ));
		    $_glowny_druk_id = $zalacznik=='1' ? $glowny_druk_id : $id;
		    $this->DB->q("INSERT INTO druki_zalaczniki (`druk`, `zalacznik`) VALUES ('$_glowny_druk_id', '$_druk_id')");
		    $this->DB->update_assoc('dokumenty', array('gid'=>$_druk_id), $item[2]);
	    }
	    
	  }
    
  
  
  }
  
  


  
  $this->DB->update_assoc('druki', $druk_data, $id);
  
  $this->S('liczniki/nastaw/dokumenty_obrabiane');
  $this->S('liczniki/nastaw/druki');
  
?>