<?
  list($projekt_id, $czytanie) = $_PARAMS;
  if( strlen($projekt_id)!=5 ) return false;
  if( !is_array($czytanie) || empty($czytanie) ) return false;
      
  $data['projekt_id'] = $projekt_id;
  $data['data'] = $czytanie['data'];
  
  $czytanie_id = $this->DB->insert_assoc_create_id('czytania_komisje', $data);
  if( $czytanie_id ) {
  
    $komisje = explode(',', $czytanie['komisje']);
    for( $i=0; $i<count($komisje); $i++ ) {
      $this->DB->insert_ignore_assoc('czytania_komisje_komisje', array(
        'czytanie_id' => $czytanie_id,
        'komisja_id' => $komisje[$i],
      ));
    }
  
    return $czytanie_id;
  } else return false;
?>