<?
  $this->S('liczniki/nastaw', array('multidebaty', $this->DB->selectCount("SELECT COUNT(*) FROM multidebaty WHERE akcept='0'")));
?>