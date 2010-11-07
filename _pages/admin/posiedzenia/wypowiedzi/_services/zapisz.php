<?
  $id = $_PARAMS['id'];
  $skrot = trim($_PARAMS['skrot']);
  
  $this->DB->update_assoc('wypowiedzi', array(
    'skrot' => addslashes($skrot),
    'akcept' => '1',
  ), $id);
  
  $this->S('liczniki/nastaw/wypowiedzi');
  
  return 4;
?>