<?
  require_once( ROOT.'/_lib/SejmParser.php' );
  $SP = new SejmParser();
  
  $sejm_ids = $SP->poslowie_lista();
  $db_ids = $this->DB->selectValues("SELECT sejm_id FROM poslowie");
  
  $new_ids = array_values( array_diff($sejm_ids, $db_ids) );
    
  foreach( $new_ids as $id ) $this->DB->insert_ignore_assoc('poslowie', array('id'=>uniqid(), 'sejm_id'=>$id));
?>