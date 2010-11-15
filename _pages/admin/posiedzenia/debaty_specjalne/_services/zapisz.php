<?
  $id = $_PARAMS['id'];
  if( strlen($id)!=5 ) return false;
  
  $tytul = $_PARAMS['tytul'];
  
  $this->DB->update_assoc('debaty_specjalne', array(
    'tytul' => addslashes($tytul),
    'akcept' => '1',
  ), $id);
  if( $this->DB->affected_rows ) {
    $this->S('liczniki/nastaw/punkty_wypowiedzi');
    return 4;
  }
?>