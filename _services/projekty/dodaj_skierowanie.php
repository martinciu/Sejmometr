<?
  list($projekt_id, $skierowanie) = $_PARAMS;
  if( strlen($projekt_id)!=5 ) return false;
  if( !is_array($skierowanie) || empty($skierowanie) ) return false;
  
    
  $data['projekt_id'] = $projekt_id;
  $data['data'] = $skierowanie['data'];
  $data['data_zalecenie'] = $skierowanie['data_zalecenie'];
  
  $skierowanie_id = $this->DB->insert_assoc_create_id('skierowania', $data);
  if( $skierowanie_id ) {
  
    $adresaci = explode(',', $skierowanie['adresat']);
    for( $i=0; $i<count($adresaci); $i++ ) {
      $this->DB->insert_ignore_assoc('skierowania_adresaci', array(
        'skierowanie_id' => $skierowanie_id,
        'autor_id' => $adresaci[$i],
      ));
    }
  
    return $skierowanie_id;
  } else return false;
?>