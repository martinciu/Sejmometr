<?
  $i = 0;
  while( $id = $this->DB->selectValue("SELECT id FROM wypowiedzi WHERE status_skrot='0' ORDER BY data_dodania DESC") ) {
    $i++;
    $skrot = $this->S('wypowiedzi/skrot', $id);
    $this->DB->update_assoc('wypowiedzi', array('skrot'=>addslashes($skrot), 'status_skrot'=>'1'), $id);
  }
  if($i) $this->S('liczniki/nastaw/wypowiedzi');
?>