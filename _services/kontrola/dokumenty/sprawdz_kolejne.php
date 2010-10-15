<?
  $limit = is_numeric( $_PARAMS ) ? $_PARAMS : 100; 
  $ids = $this->DB->selectValues("SELECT id FROM dokumenty WHERE `akcept`!='0' ORDER BY _kontrola ASC LIMIT $limit");
  
  $r = 0;
  foreach( $ids as $id ) {
    if( $this->S('kontrola/dokumenty/sprawdz', $id) ) $r++;
  }
  
  return $r;
?>