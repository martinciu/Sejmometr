<?
  $this->S('liczniki/nastaw', array('informacje_biezace', $this->DB->selectCount("SELECT COUNT(*) FROM informacje_biezace WHERE akcept='0'")));
?>