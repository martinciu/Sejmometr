<?
  $id = $_PARAMS['id'];
  if( strlen($id)!=5 ) return false;
  
  $tytul = $_PARAMS['tytul'];
  $klub_id = $_PARAMS['klub_id'];
  
  $this->DB->update_assoc('informacje_biezace', array(
    'tytul' => addslashes($tytul),
    'klub_id' => $klub_id,
    'akcept' => '1',
  ), $id);
  if( $this->DB->affected_rows ) {
    $this->S('liczniki/nastaw/informacje_biezace');
    return 4;
  }
?>