<?
  require_once( ROOT.'/_lib/SejmParser.php' );
  $SP = new SejmParser();
  
  $result = array(
    'druki' => 0,
    'dokumenty' => 0,
  );
  
  $druki = array();
  
  $session_data = $this->S('graber/session_start', 'druki/pobierz_nowe');
  
  // Pobieramy 300 najnowszych idsów druków
  $ids = $SP->druki_lista_id();
    
  if( is_array($ids) ) {
    
    // Sprawdzamy które są już w bazie
    $old_ids = $this->DB->selectValues("SELECT sejm_id FROM druki WHERE (sejm_id='".implode("' OR sejm_id='", $ids)."')");
    $ignore_ids = $this->DB->selectValues("SELECT DISTINCT(sejm_id) FROM druki_ignorowane");
    
    // Sprawdzamy które są nowe
    $new_ids = array_diff($ids, $old_ids);
    $new_ids = array_diff($new_ids, $ignore_ids);
    
    // Dołączamy druki oczekujące
    $new_ids = array_unique( array_merge($new_ids, $this->DB->selectValues("SELECT sejm_id FROM druki WHERE ilosc_dokumentow='0' ORDER BY data_dodania DESC LIMIT 50")) );
    
    // Pobieramy szczegółowe dane nowych druków
    if( is_array($new_ids) ) foreach( $new_ids as $id ) {
      $druki[] = $SP->druki_info($id);
    }
  }
      
  foreach( $druki as $druk ) {
    
    $druk_data = array(
	    'sejm_id' => $druk['id'],
	    'numer' => $druk['numer'],
	    'data' => $druk['data'],
	    'tytul_oryginalny' => $druk['tytul'],
	    'ilosc_dokumentow' => count($druk['pliki']),
	  );
	  
	  if( stripos($druk_data['tytul_oryginalny'], 'do druku')!==false ) { $druk_data['zalacznik'] = '1'; }
	  


    $pliki_count = 0;
    
	  $id = $this->DB->selectValue("SELECT id FROM druki WHERE sejm_id='".$druk_data['sejm_id']."'");	  
	  if( empty($id) ) {
	    
	    $id = $this->DB->insert_assoc_create_id('druki', $druk_data);
	    $pliki_count = count($druk['pliki']);
	    $glowny_dokument_przypisany = false;
	    $result['druki']++;
	    
	  } else {
	    
	    $druk_data['id'] = $id;
	    $druk_data['data_dodania'] = 'NOW()';
	    $this->DB->update_assoc('druki', $druk_data, $id);
	    
	    $dokument_id = $this->DB->selectValue("SELECT dokument_id FROM druki WHERE id='$id'");
	    $glowny_dokument_przypisany = !empty( $dokument_id );
	    
	    // wyrzucamy pliki danego druku, które są już w bazie
	    $stare_pliki = $this->DB->selectValues("SELECT plik FROM dokumenty WHERE gid='$id'");
	    if( is_array($druk['pliki']) ) foreach( $druk['pliki'] as &$plik ) {
	      if( in_array($plik[0], $stare_pliki) ) unset($plik);
	    }
	    
	    $pliki_count = count($stare_pliki) + count($druk['pliki']);
	    
	  }
	 
    if( $pliki_count>1 ) $this->DB->insert_ignore_assoc('druki_multi', array('id'=>$id));
	  if( $id!==false ) {
	    
	    $glowny_dokument_przypisany = false;
	    if( is_array($druk['pliki']) ) foreach( $druk['pliki'] as $plik ) {
	      $pathparts = pathinfo($plik[0]);
	      $plik_data = array(
	        'typ' => 'druk',
	        'gid' => $id,
	        'download_url' => $plik[1],
	        'plik' => $plik[0],
	        'plik_rozszerzenie' => strtolower( $pathparts['extension'] ),
	      );
	      $count = $this->DB->selectCount("SELECT COUNT(*) FROM dokumenty WHERE (typ='druk' AND plik='".$plik[0]."')");
	      if( $count===0 ) {  
		      if( $dokument_id = $this->DB->insert_assoc_create_id('dokumenty', $plik_data) ) {
		        if( !$glowny_dokument_przypisany ) {
		          $this->DB->q("UPDATE druki SET `dokument_id`='$dokument_id' WHERE `id`='$id'");
		          $glowny_dokument_przypisany = true;
		        }
		        $result['dokumenty']++;
		      }
	      } else {
	        $this->DB->insert_ignore_assoc('dokumenty_problemy', $plik_data);
	      }
	    }
	    
	    
	  }
  }
  
  $session_data[] = $result;
  $this->S('graber/session_end', $session_data);
  $this->S('liczniki/nastaw/druki');
  $this->S('liczniki/nastaw/dokumenty_obrabiane');
  
  return $result
?>