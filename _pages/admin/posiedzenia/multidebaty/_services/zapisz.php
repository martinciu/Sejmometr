<?
  $id = $_PARAMS['id'];
  if( strlen($id)!=5 ) return false;
  
  $data = array(
    'tytul' => $_PARAMS['tytul'],
    'akcept' => '1',
  );
  
  $this->DB->update_assoc('multidebaty', $data, $id);
  $this->S('liczniki/nastaw/multidebaty');
  return 4;
?>