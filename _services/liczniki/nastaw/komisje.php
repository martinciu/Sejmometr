<?
  $this->S('liczniki/nastaw', array('komisje', $this->DB->selectCount("SELECT COUNT(*) FROM komisje WHERE akcept='0'")));
?>