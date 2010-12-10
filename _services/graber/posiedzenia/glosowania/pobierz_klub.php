<?
  list($glosowanie_id, $_klub_id) = $_PARAMS;
  if( strlen($glosowanie_id)!=5 ) return false;
  
  $this->DB->q("UPDATE glosowania_kluby SET status='1', data_pobrania=NOW() WHERE glosowanie_id='$glosowanie_id' AND klub_id='$_klub_id'");
	
	$klub_id = $this->DB->selectValue("SELECT id FROM kluby WHERE id='$_klub_id'");
	
	if( empty($klub_id) ) {
	  $this->DB->q("UPDATE glosowania_kluby SET status='4', data_pobrania=NOW() WHERE glosowanie_id='$glosowanie_id' AND klub_id='$_klub_id'");
	}	else {
	  
	  require_once(ROOT.'/_lib/SejmParser.php');
		$SP = new SejmParser();
	  
	  $data = $SP->glosy( $this->DB->selectValue("SELECT url FROM glosowania_kluby WHERE glosowanie_id='$glosowanie_id' AND klub_id='$_klub_id'") );
	  if( $SP->response_status==200 ) {
	    	    
		  foreach( $data as $d ) $this->DB->insert_ignore_assoc('glosowania_glosy', array(
		    'glosowanie_id' => $glosowanie_id,
		    'czlowiek_id' => $d['posel'],
		    'klub_id' => $klub_id,
		    'glos' => $d['glos'],
		  ));
		  		  	    
	    $ilosc_glosow = $this->DB->selectCount("SELECT COUNT(*) FROM glosowania_glosy WHERE glosowanie_id='$glosowanie_id'");
			$this->DB->update_assoc('glosowania', array('ilosc_glosow'=>$ilosc_glosow), $glosowanie_id);

		  $this->DB->q("UPDATE glosowania_kluby SET klub_id='$klub_id', status='2', data_pobrania=NOW() WHERE glosowanie_id='$glosowanie_id' AND klub_id='$_klub_id'");

	  } else {
		  $this->DB->q("UPDATE glosowania_kluby SET status='3', data_pobrania=NOW() WHERE glosowanie_id='$glosowanie_id' AND klub_id='$_klub_id'");
	  }
	
	}
?>