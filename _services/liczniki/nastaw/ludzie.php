<?
  $this->S('liczniki/nastaw', array('ludzie', $this->DB->selectCount("SELECT COUNT(*) FROM ludzie WHERE akcept='0' OR avatar='0'")));
?>