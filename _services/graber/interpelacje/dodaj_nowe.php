<?
  $wszystkie = ($_PARAMS=='wszystkie');

  require_once( ROOT.'/_lib/SejmParser.php' );
  $SP = new SejmParser();
  
  $ids = $SP->interpelacje_lista_id( $wszystkie );
  $result = 0;
  
  foreach( $ids as $id ) {
    $this->DB->insert_assoc_create_id('interpelacje', array(
	    'sejm_id' => $id,
	    'typ' => 'I',
	  ));
	  if( $this->DB->affected_rows ) $result++;
	}
  
  return $result;
?>