<?
  $this->S('liczniki/nastaw', array('bas', $this->DB->selectCount("SELECT COUNT(*) FROM bas WHERE akcept='0'")));
?>