<?
  $wszystkie = ($_PARAMS == 'wszystkie');

  require_once( ROOT.'/_lib/SejmParser.php' );
  $SP = new SejmParser();
  
  $result = array(
    'druki' => 0,
    'dokumenty' => 0,
  );
  
  $druki = array();
  
  $session_data = $this->S('graber/session_start', 'druki/pobierz_nowe');
  
  // Pobieramy 300 najnowszych idsów druków
  $ids = $SP->druki_lista_id( $wszystkie );
    
  if( !empty($ids) ) foreach( $ids as $id ) $this->DB->insert_assoc_create_id('druki', array('sejm_id'=>$id));
  
  
  $session_data[] = $result;
  
  $this->S('graber/session_end', $session_data);
  $this->S('liczniki/nastaw/druki');
  
  return $result
?>