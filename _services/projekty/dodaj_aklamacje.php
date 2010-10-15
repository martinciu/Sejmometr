<?
  list($projekt_id, $aklamacja) = $_PARAMS;
  if( strlen($projekt_id)!=5 ) return false;
  if( !is_array($aklamacja) || empty($aklamacja) ) return false;
  
  $data = array();
  $data['projekt_id'] = $projekt_id;
  $data['data'] = $aklamacja['data'];
  
  $aklamacja_id = $this->DB->insert_assoc_create_id('aklamacje', $data);  
  return $aklamacja_id ? $aklamacja_id : false;
?>