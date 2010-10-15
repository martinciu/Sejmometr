<?
  $this->S('liczniki/nastaw', array('punkty_glosowania', $this->DB->selectCount("SELECT COUNT(*) FROM punkty_glosowania WHERE akcept='0'")));
?>