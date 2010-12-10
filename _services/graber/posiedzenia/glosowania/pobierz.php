<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
  
  require_once(ROOT.'/_lib/SejmParser.php');
	$SP = new SejmParser();
	
  list($sejm_id, $dzien_id) = $this->DB->selectRow("SELECT sejm_id, dzien_id FROM glosowania WHERE id='$id'");
	$this->DB->q("UPDATE glosowania SET status='1', data_pobrania=NOW() WHERE id='$id'");
  
  $data = $SP->glosowanie_info($sejm_id);
  if( $SP->response_status==200 ) {
    
    
	  $posiedzenie_numer = addslashes($data['posiedzenie_numer']);
	  $punkt = addslashes($data['punkt']);
	  
	  $this->DB->q("INSERT INTO glosowania_modele (`glosowanie_id`, `punkt`, `posiedzenie_numer`) VALUES ('$id', '$punkt', '$posiedzenie_numer') ON DUPLICATE KEY UPDATE `posiedzenie_numer`='$posiedzenie_numer', `punkt`='$punkt'");
	  
	  
	  if( $data['rodzaj']=='1' ) {
		  if( is_array($data['kluby']) ) foreach( $data['kluby'] as $klub ) {
		    $klub_id = str_replace('niez.', 'niez', $klub['klub_id']);
		    $this->DB->insert_ignore_assoc('glosowania_kluby', array(
			    'glosowanie_id' => $id,
			    'url' => $klub['url'],
			    'klub_id' => $klub_id,
			  ));
		  }
	  }
	  
	  $ilosc_klubow = $this->DB->selectCount("SELECT COUNT(*) FROM glosowania_kluby WHERE glosowanie_id='$id'");
	  
	  $this->DB->update_assoc('glosowania', array(
	    'data_pobrania' => 'NOW()',
	    'numer' => $data['numer'],
	    'czas' => $data['czas'],
	    'rodzaj' => $data['rodzaj'],
	    'tytul' => addslashes($data['tytul']),
	    'ilosc_klubow' => $ilosc_klubow,
	    'status' => '2',
	  ), $id);	  
  
  } else {
    $this->DB->q("UPDATE glosowania SET status='3', data_pobrania=NOW() WHERE id='$id'");
  }
?>