<?
  $this->S('liczniki/nastaw', array('wypowiedzi', $this->DB->selectCount("SELECT COUNT(*) FROM wypowiedzi WHERE typ='1' AND akcept='0'")));
?>