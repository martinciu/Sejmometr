<?
  require_once( ROOT.'/_lib/SejmParser.php' );
  $SP = new SejmParser();
  
  $ustawy = $SP->projekty_ustawy_lista_id();
  $uchwaly = $SP->projekty_uchwaly_lista_id();
  $inne = $SP->projekty_inne_lista_id();
  
  $projekty = array();
  if( is_array($ustawy) ) foreach($ustawy as $sejm_id) $projekty[] = array('sejm_id'=>$sejm_id, 'typ_id'=>'1');
  if( is_array($uchwaly) ) foreach($uchwaly as $sejm_id) $projekty[] = array('sejm_id'=>$sejm_id, 'typ_id'=>'2');
  if( is_array($inne) ) foreach($inne as $sejm_id) $projekty[] = array('sejm_id'=>$sejm_id, 'typ_id'=>'0');
  
  foreach( $projekty as $projekt ) {
    
    $projekty_ignore_exists = $this->DB->selectCountBoolean("SELECT COUNT(*) FROM projekty_ignore WHERE sejm_id='".$projekt['sejm_id']."'");
    $projekty_exists = $this->DB->selectCountBoolean("SELECT COUNT(*) FROM projekty WHERE sejm_id='".$projekt['sejm_id']."'");
    
    if( !$projekty_ignore_exists && !$projekty_exists ) {
      $this->DB->insert_assoc_create_id('projekty', $projekt);
    }
  }
?>