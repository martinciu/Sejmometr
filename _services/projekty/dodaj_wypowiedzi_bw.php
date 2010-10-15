<?
  list($projekt_id, $punkt) = $_PARAMS;
  if( strlen($projekt_id)!=5 ) return false;
  if( !is_array($punkt) || empty($punkt) ) return false;
  
  $data = array();
  $data['projekt_id'] = $projekt_id;
  $data['data'] = $punkt['data'];
  
  $punkt_id = $this->DB->insert_assoc_create_id('punkty_wypowiedzi_bz', $data);  
  return $punkt_id ? $punkt_id : false;
?>