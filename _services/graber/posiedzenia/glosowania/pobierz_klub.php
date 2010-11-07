<?
  list($glosowanie_id, $_klub_id) = $_PARAMS;
  if( strlen($glosowanie_id)!=5 ) return false;
  foreach( $this->DB->selectRows("SELECT id, sejm_id FROM kluby") as $klub ) $kluby[ strtolower($klub[1]) ] = $klub[0];
  $this->DB->q("UPDATE glosowania_kluby SET status='1', data_pobrania=NOW() WHERE glosowanie_id='$glosowanie_id' AND klub_id='$_klub_id'");
	
	$klub_id = $kluby[ strtolower($_klub_id) ];
	
	if( !$klub_id ) {
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
		  $this->DB->q("UPDATE glosowania_kluby SET klub_id='$klub_id', status='2', data_pobrania=NOW() WHERE glosowanie_id='$glosowanie_id' AND klub_id='$_klub_id'");
	    
	  } else {
		  $this->DB->q("UPDATE glosowania_kluby SET status='3', data_pobrania=NOW() WHERE glosowanie_id='$glosowanie_id' AND klub_id='$_klub_id'");
	  }
	
	}
?>