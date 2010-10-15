<?
  list($sejm_id, $dzien_id, $pp) = $_PARAMS;
  $pp = $pp ? '1' : '0';
  if( empty($sejm_id) ) return false;
  
  $id = $this->DB->selectValue("SELECT id FROM glosowania WHERE sejm_id='$sejm_id'");
  
  if( empty($id) ) {
    return $this->DB->insert_assoc_create_id('glosowania', array(
      'sejm_id' => $sejm_id,
      'dzien_id' => $dzien_id,
      'pp' => $pp,
    ));
  } else return $id;  
?>