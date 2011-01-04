<?
  $limit = 500;
  $i = 0;
  while( $i<$limit && $id = $this->DB->selectValue("SELECT id FROM punkty_wypowiedzi WHERE status='0'") ) {
    $i++;
    $this->DB->update_assoc('punkty_wypowiedzi', array('status'=>'1'), $id);
    $opis = $this->S('debaty/info', $id);
    $this->DB->update_assoc('punkty_wypowiedzi', array('opis'=>addslashes($opis), 'status'=>'2'), $id);
  }
  if($i) $this->S('liczniki/nastaw/punkty_wypowiedzi');
?>